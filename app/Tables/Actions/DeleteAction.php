<?php

namespace App\Tables\Actions;

class DeleteAction extends Action
{
    public string $name = 'delete';
    public string $title = 'Удалить';
    public string $icon = 'trash';
    public string $style = 'danger';

    protected string $confirmation = 'Вы уверены, что хотите удалить эту запись?';
    protected string $method = 'DELETE'; // можно переопределить
    protected bool $useAjax = false;     // пока false, генерируем стандартную форму Laravel

    /**
     * Установить текст подтверждения.
     */
    public function withConfirmation(string $message): self
    {
        $this->confirmation = $message;
        return $this;
    }

    /**
     * Отключить подтверждение.
     */
    public function withoutConfirmation(): self
    {
        $this->confirmation = '';
        return $this;
    }

    /**
     * Использовать AJAX-запрос вместо формы.
     */
    public function ajax(bool $useAjax = true): self
    {
        $this->useAjax = $useAjax;
        return $this;
    }

    public function render($model): string
    {
        $url = $this->getUrl($model);

        if ($this->useAjax) {
            // Генерируем кнопку с data-атрибутами для AJAX
            $this->attribute('data-url', $url);
            $this->attribute('data-method', $this->method);

            if ($this->confirmation) {
                $this->attribute('data-confirm', $this->confirmation);
            }

            $this->attribute('data-action', 'delete');

            $button = $this->getButton($this->attributes);

            return $button;
        }

        // Стандартная форма Laravel с DELETE методом
        $csrf = csrf_field();
        $method = method_field($this->method);

        $confirmAttr = $this->confirmation
            ? 'onclick="return confirm(\''.addslashes($this->confirmation).'\')"'
            : '';

        $button = $this->getButton($this->attributes);

        return "<form action='$url' method='POST' style='display:inline;'
                    $confirmAttr>
                    $csrf
                    $method
                    $button
                </form>";
    }

    private function getButton(array $attributes): string
    {
        return view('components.button', [
            ...[
                'type' => 'submit',
                'size' => 'sm',
                'variant' => $this->style,
                'icon' => $this->icon,
                'title' => $this->title,
                'slot' => null,
            ], ...$attributes,
        ])->toHtml();
    }
}
