@props([
    'name' => 'password',
    'label' => 'Пароль',
    'placeholder' => 'Введите пароль',
    'required' => true,
    'showToggle' => true,
    'strengthMeter' => false,
])

@php
    $id = $attributes->get('id', $name);
    $value = old($name);
    $error = $errors->first($name);

    $inputClasses = 'form-control';
    if ($error) $inputClasses .= ' is-invalid';
    if ($attributes->has('class')) $inputClasses .= ' ' . $attributes->get('class');
@endphp

<div class="password-input-group">
    @if($label)
        <label for="{{ $id }}" class="form-label d-flex justify-content-between align-items-center">
            <span>{{ $label }}</span>
            @if($showToggle)
                <button
                    type="button"
                    class="btn btn-link btn-sm password-toggle"
                    data-target="{{ $id }}"
                    style="font-size: 0.875rem; padding: 0;"
                >
                    <span class="password-toggle-show">
                        <x-main-icon name="eye" size="sm" class="me-1"/>
                        Показать
                    </span>
                    <span class="password-toggle-hide d-none">
                        <x-main-icon name="eye-slash" size="sm" class="me-1"/>
                        Скрыть
                    </span>
                </button>
            @endif
        </label>
    @endif

    <div class="input-group">
        <input
            type="password"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            class="{{ trim($inputClasses) }}"
            @if($required) required @endif
            autocomplete="current-password"
            {{ $attributes->except(['class', 'id', 'value', 'placeholder', 'required']) }}
        >

        @if($showToggle)
            <button
                type="button"
                class="btn btn-outline-secondary password-toggle-btn"
                data-target="{{ $id }}"
                title="Показать/скрыть пароль"
            >
                <x-main-icon name="eye" size="sm" class="password-toggle-icon-show"/>
                <x-main-icon name="eye-slash" size="sm" class="password-toggle-icon-hide d-none"/>
            </button>
        @endif
    </div>

    @if($strengthMeter)
        <div class="password-strength mt-2">
            <div class="progress" style="height: 4px;">
                <div class="progress-bar" role="progressbar" style="width: 0%;"></div>
            </div>
            <small class="password-strength-text text-muted mt-1 d-block"></small>
        </div>
    @endif

    @if($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Функция для переключения видимости пароля
            function togglePasswordVisibility(targetId) {
                const input = document.getElementById(targetId);
                const toggleBtn = document.querySelector(`.password-toggle-btn[data-target="${targetId}"]`);
                const toggleText = document.querySelector(`.password-toggle[data-target="${targetId}"]`);

                if (input) {
                    if (input.type === 'password') {
                        input.type = 'text';

                        // Обновляем иконки
                        if (toggleBtn) {
                            toggleBtn.querySelector('.password-toggle-icon-show').classList.add('d-none');
                            toggleBtn.querySelector('.password-toggle-icon-hide').classList.remove('d-none');
                        }

                        if (toggleText) {
                            toggleText.querySelector('.password-toggle-show').classList.add('d-none');
                            toggleText.querySelector('.password-toggle-hide').classList.remove('d-none');
                        }
                    } else {
                        input.type = 'password';

                        // Обновляем иконки
                        if (toggleBtn) {
                            toggleBtn.querySelector('.password-toggle-icon-show').classList.remove('d-none');
                            toggleBtn.querySelector('.password-toggle-icon-hide').classList.add('d-none');
                        }

                        if (toggleText) {
                            toggleText.querySelector('.password-toggle-show').classList.remove('d-none');
                            toggleText.querySelector('.password-toggle-hide').classList.add('d-none');
                        }
                    }
                }
            }

            // Обработчики для кнопок переключения
            document.querySelectorAll('.password-toggle, .password-toggle-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    togglePasswordVisibility(targetId);
                });
            });

            @if($strengthMeter)
            // Индикатор сложности пароля
            const passwordInput = document.getElementById('{{ $id }}');
            const strengthBar = passwordInput?.closest('.password-input-group')?.querySelector('.progress-bar');
            const strengthText = passwordInput?.closest('.password-input-group')?.querySelector('.password-strength-text');

            if (passwordInput && strengthBar && strengthText) {
                passwordInput.addEventListener('input', function () {
                    const password = this.value;
                    let strength = 0;
                    let text = '';
                    let color = '';

                    // Проверка длины
                    if (password.length >= 8) strength += 25;

                    // Проверка на наличие строчных букв
                    if (/[a-z]/.test(password)) strength += 25;

                    // Проверка на наличие заглавных букв
                    if (/[A-Z]/.test(password)) strength += 25;

                    // Проверка на наличие цифр и специальных символов
                    if (/[0-9]/.test(password)) strength += 15;
                    if (/[^A-Za-z0-9]/.test(password)) strength += 10;

                    // Определяем текст и цвет
                    if (strength < 30) {
                        text = 'Слишком простой';
                        color = 'var(--bs-danger)';
                    } else if (strength < 60) {
                        text = 'Средний';
                        color = 'var(--bs-warning)';
                    } else if (strength < 80) {
                        text = 'Хороший';
                        color = 'var(--bs-info)';
                    } else {
                        text = 'Отличный';
                        color = 'var(--bs-success)';
                    }

                    // Обновляем индикатор
                    strengthBar.style.width = strength + '%';
                    strengthBar.style.backgroundColor = color;
                    strengthText.textContent = text;
                    strengthText.style.color = color;
                });
            }
            @endif
        });
    </script>
@endpush
