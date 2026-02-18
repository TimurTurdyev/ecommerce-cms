<?php

namespace App\Tables\Actions;

use Closure;
use LogicException;

abstract class Action
{
    public string $name;
    public string $title;
    public string $icon;
    public string $style = 'primary';
    public array $roles = [];
    public ?Closure $authorizeCallback = null;

    // Настройка URL
    protected ?string $routeName = null;
    protected array $routeParams = [];
    protected ?string $customUrl = null;
    protected ?Closure $urlCallback = null;

    // Дополнительные HTML-атрибуты
    protected array $attributes = [];

    /**
     * Установить маршрут (имя + параметры).
     * В параметрах можно указывать строку-заполнитель, например 'id' → подставится $model->id.
     */
    public function route(string $routeName, array $params = []): self
    {
        $this->routeName = $routeName;
        $this->routeParams = $params;
        return $this;
    }

    /**
     * Установить прямой URL (если не используется именованный маршрут).
     */
    public function url(string $url): self
    {
        $this->customUrl = $url;
        return $this;
    }

    /**
     * Установить callback для динамического формирования URL.
     * Callback получает модель и должен вернуть строку URL.
     */
    public function urlCallback(callable $callback): self
    {
        $this->urlCallback = $callback;
        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    // Методы настройки внешнего вида и прав

    public function icon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function style(string $style): self
    {
        $this->style = $style;
        return $this;
    }

    public function visibleForRoles($roles): self
    {
        $this->roles = is_array($roles) ? $roles : func_get_args();
        return $this;
    }

    public function authorize(callable $callback): self
    {
        $this->authorizeCallback = $callback;
        return $this;
    }

    public function isVisible(): bool
    {
        if (empty($this->roles)) {
            return true;
        }
        $user = auth()->user();
        return $user && in_array($user->role, $this->roles);
    }

    public function isAuthorized($model): bool
    {
        if ($this->authorizeCallback) {
            return call_user_func($this->authorizeCallback, $model);
        }
        return true;
    }

    public function attribute(string $name, string $value): self
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * Рендер HTML действия.
     * Должен быть реализован в конкретном классе.
     */
    abstract public function render($model): string;

    /**
     * Получить URL для конкретной модели.
     */
    protected function getUrl($model): string
    {
        if ($this->customUrl) {
            return $this->customUrl;
        }

        if ($this->urlCallback) {
            return call_user_func($this->urlCallback, $model);
        }

        if ($this->routeName) {
            $params = [];
            foreach ($this->routeParams as $key => $value) {
                // Если значение — строка и является названием поля модели — подставляем значение
                if (is_string($value) && isset($model->{$value})) {
                    $params[$key] = $model->{$value};
                } else {
                    $params[$key] = $value;
                }
            }
            return route($this->routeName, $params);
        }

        throw new LogicException('URL not configured for action: '.static::class);
    }
}
