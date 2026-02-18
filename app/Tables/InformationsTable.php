<?php

namespace App\Tables;

use App\Models\Information;
use App\Tables\Actions\DeleteAction;
use App\Tables\Actions\EditAction;
use App\Tables\Columns\Column;
use App\Tables\Configuration\TableConfiguration;
use App\Tables\Filters\SelectFilter;
use App\Tables\Filters\TextFilter;
use Illuminate\Database\Eloquent\Builder;

class InformationsTable extends TableConfiguration
{
    protected function query(): Builder
    {
        return Information::query();
    }

    protected function columns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('title')->title('Название')->searchable()->sortable(),
            Column::make('sort_order')->title('Сортировка')->sortable(),
            Column::make('bottom')->title('В подвале')
                ->format(fn($info) => $info->bottom ? 'Да' : 'Нет'),
            Column::make('status')->title('Статус')
                ->format(fn($info) => $info->status ? 'Включено' : 'Отключено'),
        ];
    }

    protected function filters(): array
    {
        return [
            (new TextFilter('title', 'Название', 'title'))
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
                ->route('information.edit', ['information' => 'id']),

            (new DeleteAction)
                ->route('information.delete', ['information' => 'id'])
                ->withConfirmation('Статья будет удалена. Продолжить?'),
        ];
    }
}
