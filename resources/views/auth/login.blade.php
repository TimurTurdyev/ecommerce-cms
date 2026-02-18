@extends('layouts.guest')

@section('title', 'Вход в систему')

@section('auth-content')
    <div class="auth-card">
        <div class="auth-header">
            <a href="{{ url('/') }}" class="auth-logo">
                <x-main-icon name="squares-2x2" size="xl" class="auth-logo-icon"/>
                <span class="auth-logo-text">{{ config('app.name') }}</span>
            </a>

            <h1 class="auth-title">Вход в систему</h1>
            <p class="auth-subtitle">Введите свои учетные данные для доступа</p>
        </div>

        <div class="auth-body">
            @if(session('status'))
                <x-alert
                    type="success"
                    dismissible
                    icon="check-circle"
                    icon-set="heroicon"
                    class="mb-4"
                >
                    {{ session('status') }}
                </x-alert>
            @endif

            @if($errors->any())
                <x-alert
                    type="danger"
                    dismissible
                    icon="exclamation-triangle"
                    icon-set="heroicon"
                    title="Ошибка авторизации"
                    class="mb-4"
                >
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="mb-4">
                    <x-forms.input
                        name="email"
                        type="email"
                        label="Email адрес"
                        placeholder="Введите ваш email"
                        required
                        autofocus
                        autocomplete="email"
                        icon="envelope"
                        icon-set="heroicon"
                    />
                </div>

                <div class="mb-4">
                    <x-auth.password-input
                        name="password"
                        label="Пароль"
                        placeholder="Введите ваш пароль"
                        required
                        autocomplete="current-password"
                        :show-toggle="true"
                        :strength-meter="true"
                    />

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        {{-- Запомнить меня --}}
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="remember"
                                id="remember"
                            >
                            <label class="form-check-label" for="remember">
                                Запомнить меня
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 mb-4" id="loginButton">
                    <span class="login-button-text">Войти в систему</span>
                    <span class="login-button-loading d-none">
                        <span class="spinner-border spinner-border-sm me-2"></span>
                        Вход...
                    </span>
                </button>

                <div class="text-center">
                    <button
                        type="button"
                        class="btn btn-link text-decoration-none"
                        id="authThemeToggle"
                        style="color: oklch(var(--bs-base-500));"
                    >
                        <x-main-icon name="sun" set="heroicon" size="sm" class="me-2 theme-icon-light"/>
                        <x-main-icon name="moon" set="heroicon" size="sm" class="me-2 theme-icon-dark d-none"/>
                        <span class="theme-text">Светлая тема</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="auth-footer">
            <p class="mb-2">&copy; {{ date('Y') }} {{ config('app.name') }}. Все права защищены.</p>
            <p class="mb-0 small">v{{ config('app.version', '1.0.0') }}</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');

            if (loginForm) {
                loginForm.addEventListener('submit', function (e) {
                    const buttonText = loginButton.querySelector('.login-button-text');
                    const buttonLoading = loginButton.querySelector('.login-button-loading');

                    if (buttonText && buttonLoading) {
                        buttonText.classList.add('d-none');
                        buttonLoading.classList.remove('d-none');
                        loginButton.disabled = true;
                    }
                });
            }

            const themeToggle = document.getElementById('authThemeToggle');
            const themeText = themeToggle?.querySelector('.theme-text');
            const themeIconLight = themeToggle?.querySelector('.theme-icon-light');
            const themeIconDark = themeToggle?.querySelector('.theme-icon-dark');

            if (themeToggle) {
                themeToggle.addEventListener('click', function () {
                    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                    document.documentElement.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('theme', newTheme);

                    if (themeText) {
                        themeText.textContent = newTheme === 'dark' ? 'Темная тема' : 'Светлая тема';
                    }

                    if (themeIconLight && themeIconDark) {
                        if (newTheme === 'dark') {
                            themeIconLight.classList.add('d-none');
                            themeIconDark.classList.remove('d-none');
                        } else {
                            themeIconLight.classList.remove('d-none');
                            themeIconDark.classList.add('d-none');
                        }
                    }
                });

                const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                if (themeText) {
                    themeText.textContent = currentTheme === 'dark' ? 'Темная тема' : 'Светлая тема';
                }

                if (themeIconLight && themeIconDark) {
                    if (currentTheme === 'dark') {
                        themeIconLight.classList.add('d-none');
                        themeIconDark.classList.remove('d-none');
                    } else {
                        themeIconLight.classList.remove('d-none');
                        themeIconDark.classList.add('d-none');
                    }
                }
            }

            const emailInput = document.querySelector('input[name="email"]');
            if (emailInput) {
                emailInput.focus();
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.target.matches('textarea, [contenteditable]')) {
                    const activeElement = document.activeElement;
                    if (activeElement.matches('input, select, button')) {
                        return;
                    }

                    const submitButton = loginForm?.querySelector('button[type="submit"]');
                    if (submitButton && !submitButton.disabled) {
                        e.preventDefault();
                        submitButton.click();
                    }
                }
            });
        });
    </script>
@endpush
