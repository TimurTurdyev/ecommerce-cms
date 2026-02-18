<?php

namespace App\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filter
{
    public string $key;

    public string $label;

    public array $roles = [];

    protected mixed $value = null;

    protected ?string $nullMode = null;

    public function __construct(string $key, string $label)
    {
        $this->key = $key;
        $this->label = $label;
    }

    abstract public function apply(Builder $query, $value): Builder;

    abstract public function render(): string;

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

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function loadFromRequest(Request $request): self
    {
        if ($request->has($this->key)) {
            $this->setValue($request->input($this->key));
        }

        return $this;
    }

    public function null(): self
    {
        $this->nullMode = 'null';

        return $this;
    }

    public function notNull(): self
    {
        $this->nullMode = 'not_null';

        return $this;
    }

    public function empty(): self
    {
        $this->nullMode = 'empty';

        return $this;
    }

    public function notEmpty(): self
    {
        $this->nullMode = 'not_empty';

        return $this;
    }

    public function hasNullMode(): bool
    {
        return $this->nullMode !== null;
    }

    protected function applyNullMode(Builder $query, string $attribute): Builder
    {
        return match ($this->nullMode) {
            'null' => $query->whereNull($attribute),
            'not_null' => $query->whereNotNull($attribute),
            'empty' => $query->where(function ($q) use ($attribute) {
                $q->whereNull($attribute)
                    ->orWhere($attribute, '');
            }),
            'not_empty' => $query->where(function ($q) use ($attribute) {
                $q->whereNotNull($attribute)
                    ->where($attribute, '!=', '');
            }),
            default => $query,
        };
    }
}
