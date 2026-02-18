<?php

namespace App\Tables;

use App\Models\Category;
use App\Tables\Actions\DeleteAction;
use App\Tables\Actions\EditAction;
use App\Tables\Columns\Column;
use App\Tables\Configuration\TableConfiguration;
use App\Tables\Filters\SelectFilter;
use App\Tables\Filters\TextFilter;
use Illuminate\Database\Eloquent\Builder;

class CategoriesTable extends TableConfiguration
{
    protected function query(): Builder
    {
        return Category::query();
    }

    protected function columns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Название')->searchable()->sortable(),
            Column::make('sort_order')->title('Сортировка')->sortable(),
            Column::make('status')->title('Статус')
                ->format(fn($category) => $category->status ? 'Включено' : 'Отключено'),
        ];
    }

    protected function filters(): array
    {
        return [
            (new TextFilter('name', 'Название', 'name'))
                ->visibleForRoles('admin'),

            (new SelectFilter('status', 'Статус', 'status', [
                true => 'Включено',
                false => 'Отключено',
            ])),
        ];
    }

    protected function actions(): array
    {
        return [
            (new EditAction)
                ->route('category.edit', ['category' => 'id']),

            (new DeleteAction)
                ->route('category.delete', ['category' => 'id'])
                ->withConfirmation('Категория будет удалена. Продолжить?'),
        ];
    }
}
