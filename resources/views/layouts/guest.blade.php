<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel')) - Авторизация</title>

    {{-- Preconnect для шрифтов --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Inter Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Vite --}}
    @vite(['resources/scss/app.scss'])

    {{-- Стили --}}
    @stack('styles')

    {{-- Скрипты в head --}}
    @stack('head-scripts')

    <style>
        :root {
            --bs-base-stroke: #d2d2d2;
        }
        /* Дополнительные стили для страницы авторизации */
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg,
            oklch(var(--bs-base-50)) 0%,
            oklch(var(--bs-base-100)) 50%,
            oklch(var(--bs-base-200)) 100%
            );
            padding: 1rem;
        }

        [data-bs-theme="dark"] .auth-page {
            background: linear-gradient(135deg,
            oklch(var(--bs-base-900)) 0%,
            oklch(var(--bs-base-800)) 50%,
            oklch(var(--bs-base-700)) 100%
            );
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .auth-card {
            background: var(--bs-base-default);
            border: 1px solid var(--bs-base-stroke);
            border-radius: var(--bs-border-radius-xl);
            box-shadow: var(--bs-box-shadow-lg);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .auth-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--bs-box-shadow-lg), 0 10px 25px -5px var(--bs-base-stroke);
        }

        .auth-header {
            text-align: center;
            padding: 2rem 2rem 0;
        }

        .auth-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            margin-bottom: 1.5rem;
        }

        .auth-logo-icon {
            color: oklch(var(--bs-primary));
            width: 2.5rem;
            height: 2.5rem;
        }

        .auth-logo-text {
            font-size: 1.5rem;
            font-weight: 600;
            color: oklch(var(--bs-base-text));
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: oklch(var(--bs-base-text));
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            color: oklch(var(--bs-base-500));
            margin-bottom: 2rem;
        }

        .auth-body {
            padding: 0 2rem 2rem;
        }

        .auth-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid oklch(var(--bs-base-stroke));
            text-align: center;
            color: oklch(var(--bs-base-500));
            font-size: 0.875rem;
        }

        .auth-footer a {
            color: oklch(var(--bs-primary));
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .auth-divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: oklch(var(--bs-base-400));
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: oklch(var(--bs-base-stroke));
        }

        .auth-divider span {
            padding: 0 1rem;
            font-size: 0.875rem;
        }

        /* Адаптивность */
        @media (max-width: 640px) {
            .auth-header,
            .auth-body,
            .auth-footer {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            .auth-card {
                border-radius: var(--bs-border-radius-lg);
            }
        }

        @media (max-width: 480px) {
            .auth-header,
            .auth-body,
            .auth-footer {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
</head>
<body class="auth-page">
<div class="auth-container">
    @yield('auth-content')
</div>

{{-- Скрипты --}}
@stack('scripts')

<script>
    // Автоматическое определение темы для страницы авторизации
    document.addEventListener('DOMContentLoaded', function() {
        // Проверяем сохраненную тему или системные предпочтения
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (!savedTheme && systemPrefersDark) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
    });
</script>
</body>
</html>
