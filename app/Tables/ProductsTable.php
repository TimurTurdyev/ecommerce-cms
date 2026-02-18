<?php

namespace App\Tables;

use App\Models\Product;
use App\Tables\Actions\DeleteAction;
use App\Tables\Actions\EditAction;
use App\Tables\Columns\Column;
use App\Tables\Configuration\TableConfiguration;
use App\Tables\Filters\SelectFilter;
use App\Tables\Filters\TextFilter;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable extends TableConfiguration
{
    protected function query(): Builder
    {
        return Product::query()->with('manufacturer');
    }

    protected function columns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('image')->title('Изображение')
                ->format(fn($product) => $product->image ? '<img src="'.$product->image.'" width="50">' : ''),
            Column::make('name')->title('Название')->searchable()->sortable(),
            Column::make('model')->title('Модель')->searchable()->sortable(),
            Column::make('sku')->title('Артикул')->searchable(),
            Column::make('manufacturer.name')->title('Производитель'),
            Column::make('quantity')->title('Кол-во')->sortable(),
            Column::make('price')->title('Цена')->sortable(),
            Column::make('status')->title('Статус')
                ->format(fn($product) => $product->status ? 'Включено' : 'Отключено'),
        ];
    }

    protected function filters(): array
    {
        return [
            (new TextFilter('name', 'Название', 'name'))
                ->visibleForRoles('admin'),

            (new TextFilter('model', 'Модель', 'model')),

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
                ->route('product.edit', ['product' => 'id']),

            (new DeleteAction)
                ->route('product.delete', ['product' => 'id'])
                ->withConfirmation('Товар будет удалён. Продолжить?'),
        ];
    }
}
