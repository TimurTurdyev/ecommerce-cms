<?php

namespace App\Tables;

use App\Models\Option;
use App\Tables\Actions\DeleteAction;
use App\Tables\Actions\EditAction;
use App\Tables\Columns\Column;
use App\Tables\Configuration\TableConfiguration;
use App\Tables\Filters\SelectFilter;
use App\Tables\Filters\TextFilter;
use Illuminate\Database\Eloquent\Builder;

class OptionsTable extends TableConfiguration
{
    protected function query(): Builder
    {
        return Option::query();
    }

    protected function columns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Название')->searchable()->sortable(),
            Column::make('type')->title('Тип')
                ->format(fn ($option) => Option::TYPES[$option->type] ?? $option->type),
            Column::make('sort_order')->title('Сортировка')->sortable(),
            Column::make('status')->title('Статус')
                ->format(fn ($option) => $option->status ? 'Включено' : 'Отключено'),
        ];
    }

    protected function filters(): array
    {
        return [
            (new TextFilter('name', 'Название', 'name')),

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
                ->route('option.edit', ['option' => 'id']),

            (new DeleteAction)
                ->route('option.delete', ['option' => 'id'])
                ->withConfirmation('Опция будет удалена. Продолжить?'),
        ];
    }
}
