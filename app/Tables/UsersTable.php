<?php

namespace App\Tables;

use App\Models\User;
use App\Tables\Actions\DeleteAction;
use App\Tables\Actions\EditAction;
use App\Tables\Columns\Column;
use App\Tables\Configuration\TableConfiguration;
use App\Tables\Filters\DateFilter;
use App\Tables\Filters\FilterGroup;
use App\Tables\Filters\SelectFilter;
use App\Tables\Filters\TextFilter;
use Illuminate\Database\Eloquent\Builder;

class UsersTable extends TableConfiguration
{
    protected function query(): Builder
    {
        return User::query();
    }

    protected function columns(): array
    {
        return [
            Column::make('id')->title('ID')->visibleForRoles('admin'),
            Column::make('name')->title('Имя')->searchable()->sortable(),
            Column::make('email')->title('Email')->searchable()->sortable(),
            Column::make('role')->title('Роль')->visibleForRoles('admin', 'manager'),
            Column::make('created_at')->title('Дата регистрации')
                ->format(fn($user) => $user->created_at->format('d.m.Y H:i'))
                ->sortable(),
        ];
    }

    protected function filters(): array
    {
        return [
            (new SelectFilter('role', 'Роль', 'role', User::ROLES))
                ->visibleForRoles('admin'),

            (new FilterGroup())
                ->addFilter((new DateFilter('created', 'Дата создания', 'created_at')))
                ->addFilter((new TextFilter('email', 'Email', 'email')))
                ->visibleForRoles('admin'),

//            (new TextFilter('email', 'Email', 'email'))
//                ->visibleForRoles('admin'),
        ];
    }

    protected function actions(): array
    {
        return [
            (new EditAction)
                ->visibleForRoles('admin')
                ->route('user.edit', ['user' => 'id']),  // 'id' — поле модели

            (new DeleteAction)
                ->visibleForRoles('admin')
                ->route('user.delete', ['user' => 'id'])
                ->withConfirmation('Все связанные данные будут удалены. Продолжить?')
                ->ajax(false),   // или ->ajax(true) для AJAX-удаления

            // Можно также задать прямой URL:
            (new EditAction)
                ->url('/custom/edit/url')
                ->authorize(fn($user) => auth()->user()->can('update', $user))
                ->title('Править'),
        ];
    }
}
