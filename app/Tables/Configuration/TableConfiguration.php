<?php

namespace App\Tables\Configuration;

use App\Tables\Filters\Filter;
use App\Tables\Filters\FilterGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class TableConfiguration
{
    protected Request $request;

    protected array $columns = [];

    protected array $filters = [];

    protected array $actions = [];

    protected Builder $query;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->query = $this->query();
        $this->columns = $this->columns();
        $this->filters = $this->filters();
        $this->actions = $this->actions();
        $this->loadFiltersFromRequest();
    }

    abstract protected function query(): Builder;

    // Обязательные методы для реализации

    abstract protected function columns(): array;

    abstract protected function filters(): array;

    abstract protected function actions(): array;

    protected function loadFiltersFromRequest(): void
    {
        /** @var FilterGroup|Filter $filter */
        foreach ($this->filters as $filter) {
            $filter->loadFromRequest($this->request);
        }
    }

    // Геттеры

    public function getQuery(): Builder
    {
        return $this->query;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getActions(): array
    {
        return $this->actions;
    }
}
