<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('description', config('app.description', ''))">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    {{-- Preconnect для шрифтов --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Inter Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @stack('styles')
    @stack('head-scripts')
</head>
<body class="layout-modern">
{{-- Мобильное меню (скрыто на десктопе) --}}
@include('layouts.partials.mobile-menu')

{{-- Сайдбар --}}
@include('layouts.partials.sidebar')
<style>
    /* Делаем контент растягивающимся */
    .content-area {
        flex: 1 0 auto;
    }

    /* Прилипающий футер */
    .main-footer {
        flex-shrink: 0;
    }
</style>
{{-- Основной контент --}}
<div class="main-content">
    {{-- Верхняя панель --}}
    @include('layouts.partials.header')

    {{-- Основная область --}}
    <main class="content-area">
        <div class="container-fluid py-4">
            {{-- Заголовок страницы --}}
            @hasSection('page-header')
                @yield('page-header')
            @else
                <x-page-header
                    :title="$title ?? null"
                    :breadcrumbs="$breadcrumbs ?? []"
                />
            @endif

            {{-- Контент --}}
            @yield('content')
        </div>
    </main>

    {{-- Нижняя панель (опционально) --}}
    @if($showFooter ?? true)
        @include('layouts.partials.footer')
    @endif
</div>

{{-- Модальные окна --}}
@stack('modals')

{{-- Скрипты --}}
@stack('scripts')

{{-- Toasts (уведомления) --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toast-container"></div>
</body>
</html>
