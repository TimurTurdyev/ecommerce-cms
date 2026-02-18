<?php

namespace App\Tables\Renderers;

use App\Tables\Columns\Column;
use App\Tables\Configuration\TableConfiguration;
use App\Tables\Filters\Filter;
use App\Tables\Filters\FilterGroup;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TableRenderer
{
    protected TableConfiguration $config;

    protected Request $request;

    protected Builder $query;

    protected int $perPage = 15;

    protected string $template = 'components.table-ajax';

    public function __construct(TableConfiguration $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
        $this->query = clone $config->getQuery();
        $this->perPage = $request->input('per_page', 15);
    }

    public function render(): View
    {
        $this->applyFilters();
        $this->applySearch();
        $this->applySorting();
        $items = $this->paginate();

        return view($this->template, [
            'config' => $this->config,
            'items' => $items,
            'request' => $this->request,
        ]);
    }

    protected function applyFilters(): void
    {
        foreach ($this->config->getFilters() as $filter) {
            if ($filter instanceof FilterGroup) {
                $this->applyFilterGroup($filter);
            } elseif ($filter instanceof Filter) {
                $this->applyFilter($filter);
            }
        }
    }

    protected function applyFilterGroup(FilterGroup $group): void
    {
        if ($group->isVisible()) {
            $group->apply($this->query);
        }
    }

    protected function applyFilter(Filter $filter): void
    {
        $filterKey = $filter->key;
        if ($filter->isVisible() && $this->request->has($filterKey)) {
            $value = $this->request->input($filterKey);
            if ($value !== '' && $value !== null) {
                $filter->apply($this->query, $value);
            }
        }
    }

    protected function applySearch(): void
    {
        $search = $this->request->input('search');
        if (empty($search)) {
            return;
        }

        $this->query->where(function ($q) use ($search) {
            /** @var Column $column */
            foreach ($this->config->getColumns() as $column) {
                if ($column->isVisible() && $column->searchable) {
                    if ($column->searchCallback) {
                        call_user_func($column->searchCallback, $q, $search);
                    } else {
                        $q->orWhere($column->name, 'LIKE', "%{$search}%");
                    }
                }
            }
        });
    }

    protected function applySorting(): void
    {
        $sort = $this->request->input('sort');
        $direction = $this->request->input('direction', 'asc');

        if (!$sort) {
            // сортировка по умолчанию — из конфига или по первому orderable
            /** @var Column $column */
            foreach ($this->config->getColumns() as $column) {
                if ($column->isVisible() && $column->orderable) {
                    if ($column->orderColumn) {
                        $this->query->orderByRaw($column->orderColumn.' '.$direction);
                    } else {
                        $this->query->orderBy($column->name, $direction);
                    }
                    break;
                }
            }

            return;
        }

        // ищем колонку с таким data или name
        $column = collect($this->config->getColumns())
            ->first(fn($col) => $col->isVisible() && ($col->data === $sort || $col->name === $sort) && $col->orderable);

        if ($column) {
            if ($column->orderColumn) {
                $this->query->orderBy($column->orderColumn, $direction);
            } else {
                $this->query->orderBy($column->name, $direction);
            }
        }
    }

    protected function paginate(): LengthAwarePaginator
    {
        $currentPage = $this->request->input('page', 1);

        return $this->query->paginate($this->perPage, ['*'], 'page', $currentPage);
    }
}
