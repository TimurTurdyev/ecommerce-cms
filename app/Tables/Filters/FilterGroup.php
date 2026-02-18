<?php

namespace App\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;

class FilterGroup
{
    public array $roles = [];
    public array $filters = [];

    public string $logical = 'and'; // 'and' | 'or'

    public function __construct(string $logical = 'and')
    {
        $this->logical = $logical;
    }

    public function addFilter(Filter $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function or(): self
    {
        $this->logical = 'or';

        return $this;
    }

    public function visibleForRoles($roles): self
    {
        $this->roles = is_array($roles) ? $roles : func_get_args();

        /** @var Filter $filter */
        foreach ($this->filters as $filter) {
            $filter->visibleForRoles($roles);
        }

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

    public function apply(Builder $query): Builder
    {
        $filters = $this->filters;
        $applyMethod = $this->logical === 'or' ? 'orWhere' : 'where';

        $query->where(static function ($groupQuery) use ($filters, $applyMethod) {
            /** @var Filter $filter */
            foreach ($filters as $filter) {
                $groupQuery->{$applyMethod}(function ($q) use ($filter) {
                    $filter->apply($q, $filter->getValue());
                });
            }
        });

        return $query;
    }

    public function loadFromRequest($request): self
    {
        /** @var Filter $filter */
        foreach ($this->filters as $filter) {
            $filter->loadFromRequest($request);
        }

        return $this;
    }

    public function render(): string
    {
        $html = '';

        foreach ($this->filters as $filter) {
            $html .= $filter->render();
        }

        return $html;
    }
}
