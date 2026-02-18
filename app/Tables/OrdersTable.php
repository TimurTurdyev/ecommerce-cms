<?php

namespace App\Tables;

use App\Models\Order;
use App\Tables\Actions\EditAction;
use App\Tables\Columns\Column;
use App\Tables\Configuration\TableConfiguration;
use App\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable extends TableConfiguration
{
    protected function query(): Builder
    {
        return Order::query()->with('user');
    }

    protected function columns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('created_at')->title('Дата')
                ->format(fn($order) => $order->created_at->format('d.m.Y H:i'))
                ->sortable(),
            Column::make('firstname')->title('Имя'),
            Column::make('lastname')->title('Фамилия'),
            Column::make('email')->title('Email'),
            Column::make('telephone')->title('Телефон'),
            Column::make('total')->title('Сумма')->sortable(),
            Column::make('status')->title('Статус')
                ->format(fn($order) => Order::STATUSES[$order->status] ?? $order->status),
        ];
    }

    protected function filters(): array
    {
        return [
            (new SelectFilter('status', 'Статус', 'status', Order::STATUSES)),
        ];
    }

    protected function actions(): array
    {
        return [
            (new EditAction)
                ->route('order.edit', ['order' => 'id'])
                ->title('Просмотр'),
        ];
    }
}
