@extends('layouts.app')

@php
    $title = 'Добро пожаловать в систему!';
@endphp

@section('title', $title)

@section('page-header')
    <x-page-header
        :title="$title"
    >
    </x-page-header>
@endsection

@push('styles')
    @vite(['resources/scss/stats.scss'])
@endpush

@section('content')
    <x-card>
        <x-slot:title>
            Статистика
        </x-slot:title>
        {{-- Сетка статистик --}}
        <div class="row">
            <div class="col-md-3">
                <x-stats.card
                    title="Общая выручка"
                    value="2450000"
                    icon="currency-dollar"
                    iconColor="success"
                    change="12.5"
                    description="За текущий месяц"
                    valueFormat="currency"
                />
            </div>
            <div class="col-md-3">
                <x-stats.card
                    title="Новые пользователи"
                    value="1250"
                    icon="user-plus"
                    iconColor="primary"
                    change="8.2"
                    description="Зарегистрировались за неделю"
                />
            </div>
            <div class="col-md-3">
                <x-stats.card
                    title="Конверсия"
                    value="4.8"
                    icon="arrow-trending-up"
                    iconColor="warning"
                    change="-2.1"
                    description="С начала месяца"
                    valueFormat="percent"
                    decimals="1"
                />
            </div>
            <div class="col-md-3">
                <x-stats.card
                    title="Средний чек"
                    value="3420"
                    icon="shopping-cart"
                    iconColor="info"
                    change="5.7"
                    description="По всем заказам"
                    valueFormat="currency"
                />
            </div>
        </div>

        {{-- Вторая строка --}}
        <div class="row mt-4 g-3">
            <div class="col-md-6">
                <x-stats.trend
                    title="Продажи"
                    value="2450"
                    :data="[65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40]"
                    period="за 14 дней"
                    change="15.3"
                    color="success"
                />
            </div>

            <div class="col-md-6">
                <x-stats.comparison
                    title="Посетители"
                    current="8452"
                    previous="7210"
                    currentLabel="За эту неделю"
                    previousLabel="За прошлую неделю"
                    showChange
                    showPercentage
                />
            </div>
        </div>

        {{-- Третья строка --}}
        <div class="row mt-4 g-3">
            <div class="col-md-8">
                <x-stats.progress
                    title="Выполнение плана продаж"
                    value="2450000"
                    total="3000000"
                    label="2.45M из 3M"
                    color="success"
                    showValue
                    showPercentage
                    height="8px"
                />

                <div class="mt-3">
                    <x-stats.list
                        :items="[
                        [
                            'label' => 'Веб-сайт',
                            'value' => '1,240',
                            'change' => 12.5,
                            'icon' => 'globe-alt',
                            'iconColor' => 'primary',
                        ],
                        [
                            'label' => 'Мобильное приложение',
                            'value' => '890',
                            'change' => 8.2,
                            'icon' => 'device-phone-mobile',
                            'iconColor' => 'success',
                        ],
                        [
                            'label' => 'Рекламные кампании',
                            'value' => '320',
                            'change' => -2.1,
                            'icon' => 'megaphone',
                            'iconColor' => 'warning',
                        ],
                    ]"
                        divided
                    />
                </div>
            </div>

            <div class="col-md-4">
                <x-stats.kpi
                    title="NPS"
                    value="42"
                    target="50"
                    format="number"
                    suffix=" баллов"
                    icon="star"
                    statusText="Хорошо"
                    compact
                />

                <div class="mt-3">
                    <x-stats.chart
                        type="pie"
                        title="Распределение по каналам"
                        :data="[
                        'datasets' => [[
                            'label' => 'Каналы',
                            'data' => [35, 25, 20, 15, 5],
                        ]],
                        'labels' => ['Поиск', 'Соцсети', 'Email', 'Прямые', 'Другое'],
                    ]"
                        showLegend
                        height="200"
                    />
                </div>
            </div>
        </div>
    </x-card>
@endsection
