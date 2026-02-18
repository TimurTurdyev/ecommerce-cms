@extends('layouts.app')

@php
    $title = 'Заказ #' . $order->id;
    $breadcrumbs = [
        ['label' => 'Главная', 'url' => route('dashboard')],
        ['label' => 'Заказы', 'url' => route('order.index')],
        ['label' => $title],
    ];
@endphp

@section('title', $title)

@section('page-header')
    <x-page-header
        :title="$title"
        :breadcrumbs="$breadcrumbs"
    />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <x-card>
                <x-slot:header>
                    <h5>Товары</h5>
                </x-slot:header>
                <x-slot:body>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Цена</th>
                                <th>Кол-во</th>
                                <th>Итого</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ number_format($product->price, 2, '.', ' ') }} ₽</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>{{ number_format($product->total, 2, '.', ' ') }} ₽</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Итого:</th>
                                <th>{{ number_format($order->total, 2, '.', ' ') }} ₽</th>
                            </tr>
                        </tfoot>
                    </table>
                </x-slot:body>
            </x-card>

            <x-card class="mt-3">
                <x-slot:header>
                    <h5>История статусов</h5>
                </x-slot:header>
                <x-slot:body>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Статус</th>
                                <th>Комментарий</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->histories as $history)
                                <tr>
                                    <td>{{ $history->created_at->format('d.m.Y H:i') }}</td>
                                    <td>{{ $statuses[$history->status] ?? $history->status }}</td>
                                    <td>{{ $history->comment }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">История пуста</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-slot:body>
            </x-card>
        </div>
        <div class="col-md-4">
            <x-card>
                <x-slot:header>
                    <h5>Покупатель</h5>
                </x-slot:header>
                <x-slot:body>
                    <p><strong>Имя:</strong> {{ $order->firstname }} {{ $order->lastname }}</p>
                    <p><strong>Email:</strong> {{ $order->email }}</p>
                    <p><strong>Телефон:</strong> {{ $order->telephone }}</p>
                </x-slot:body>
            </x-card>

            <x-card class="mt-3">
                <x-slot:header>
                    <h5>Адрес оплаты</h5>
                </x-slot:header>
                <x-slot:body>
                    <p>{{ $order->payment_firstname }} {{ $order->payment_lastname }}</p>
                    <p>{{ $order->payment_address }}</p>
                    <p>{{ $order->payment_city }}, {{ $order->payment_postcode }}</p>
                    <p>{{ $order->payment_country }}</p>
                </x-slot:body>
            </x-card>

            <x-card class="mt-3">
                <x-slot:header>
                    <h5>Адрес доставки</h5>
                </x-slot:header>
                <x-slot:body>
                    <p>{{ $order->shipping_firstname }} {{ $order->shipping_lastname }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_postcode }}</p>
                    <p>{{ $order->shipping_country }}</p>
                </x-slot:body>
            </x-card>

            <x-card class="mt-3">
                <x-slot:header>
                    <h5>Изменить статус</h5>
                </x-slot:header>
                <x-slot:body>
                    <x-forms.form method="post" action="{{ route('order.update', $order) }}">
                        @csrf
                        @method('put')
                        <div class="mb-3">
                            <x-forms.select name="status" label="Статус" :options="$statuses" value="{{ old('status', $order->status) }}" />
                        </div>
                        <div class="mb-3">
                            <x-forms.textarea name="comment" label="Комментарий">{{ old('comment') }}</x-forms.textarea>
                        </div>
                        <x-button variant="primary" type="submit">Сохранить</x-button>
                    </x-forms.form>
                </x-slot:body>
            </x-card>
        </div>
    </div>
@endsection
