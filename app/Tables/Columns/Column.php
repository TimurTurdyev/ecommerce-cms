<?php

namespace App\Tables\Columns;

use Closure;

class Column
{
    public string $data;
    public string $name;
    public string $title;
    public bool $searchable = true;
    public bool $orderable = true;  // сортируемая колонка (по умолчанию true)
    public ?Closure $format = null;
    public array $roles = [];
    public ?string $orderColumn = null;
    public ?Closure $searchCallback = null;

    public function __construct(string $data, ?string $name = null)
    {
        $this->data = $data;
        $this->name = $name ?? $data;
        $this->title = ucfirst(str_replace('_', ' ', $data));
    }

    public static function make(string $data, ?string $name = null): self
    {
        return new self($data, $name);
    }

    public function searchUsing(callable $callback): self
    {
        $this->searchCallback = $callback;
        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function searchable(bool $searchable = true): self
    {
        $this->searchable = $searchable;
        return $this;
    }

    /**
     * Алиас для sortable() — семантически соответствует DataTables
     */
    public function orderable(bool $orderable = true): self
    {
        return $this->sortable($orderable);
    }

    /**
     * Включает/выключает возможность сортировки колонки
     */
    public function sortable(bool $orderable = true): self
    {
        $this->orderable = $orderable;
        return $this;
    }

    public function orderBy(string $expression): self
    {
        $this->orderColumn = $expression;
        return $this;
    }

    public function format(callable $callback): self
    {
        $this->format = $callback;
        return $this;
    }

    public function visibleForRoles($roles): self
    {
        $this->roles = is_array($roles) ? $roles : func_get_args();
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

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'name' => $this->name,
            'title' => $this->title,
            'searchable' => $this->searchable,
            'orderable' => $this->orderable,
        ];
    }
}
