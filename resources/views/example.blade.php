@extends('layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Дашборд', 'url' => '#'],
        ['label' => 'Пользователи', 'url' => '#'],
        ['label' => 'Создание'],
    ];
@endphp

@section('title', 'Создание пользователя')

@section('page-header')
    <x-page-header
        title="Создание пользователя"
        subtitle="Заполните форму для добавления нового пользователя"
        :breadcrumbs="$breadcrumbs"
    >
        <x-slot:actions>
            <x-button variant="outline-secondary" icon="arrow-left" href="#">
                Назад
            </x-button>
            <x-button variant="primary" icon="plus" type="submit" form="createUserForm">
                Сохранить
            </x-button>
        </x-slot:actions>
    </x-page-header>
@endsection

@section('content')
    <h2>Аккордеон</h2>
    <x-accordion id="faqAccordion" class="mb-5">
        <x-accordion-item
            header="Как создать аккаунт?"
            show="true"
            parent="faqAccordion"
        >
            Перейдите на страницу регистрации и заполните форму.
        </x-accordion-item>

        <x-accordion-item
            parent="faqAccordion"
            header="Как сбросить пароль?">
            Нажмите "Забыли пароль" на странице входа.
        </x-accordion-item>
    </x-accordion>
    <div class="row">
        <div class="col-md-6">
            <h2>Алерт</h2>
            <x-alert type="success" dismissible icon="check-circle" title="Успешно!">
                Данные успешно сохранены.
            </x-alert>

            <x-alert type="info" dismissible icon="check-circle" title="Информация!">
                Данные успешно обновлены.
            </x-alert>

            <x-alert type="warning" dismissible>
                <strong>Внимание!</strong> Заканчивается место на диске.
            </x-alert>

            <x-alert type="danger" icon="exclamation-triangle">
                <ul class="mb-0">
                    <li>Ошибка валидации email</li>
                    <li>Пароль слишком короткий</li>
                </ul>
            </x-alert>
        </div>
        <div class="col-md-6 position-relative">
            <h2>Бейдж</h2>
            {{-- Простой бейдж --}}
            <x-badge type="primary">Новый</x-badge>

            {{-- Бейдж-пилюля --}}
            <x-badge type="success" pill>Активный</x-badge>

            {{-- Бейдж со счетчиком --}}
            <x-button class="position-relative" size="sm">
                Кнопка с уведомлением
                <x-badge
                    type="danger"
                    position="top-0 start-100 translate-middle"
                    notification="5"
                    class="p-2"
                >
                    Уведомления
                </x-badge>
            </x-button>

            {{-- Бейдж-ссылка --}}
            <x-badge
                type="info"
                href="/categories/programming"
                class="text-decoration-none"
            >
                Программирование
            </x-badge>
            <hr>
            <h2>ХК</h2>
            <x-breadcrumb
                :items="[
        ['label' => 'Блог', 'url' => '#'],
        ['label' => 'Категории', 'url' => '#'],
        ['label' => 'Технологии', 'url' => '#'],
    ]"
                current="Искусственный интеллект"
                homeIcon="house"
                homeText="Главная"
            />

            <hr>
            <h2>Кнопки</h2>
            {{-- Базовая кнопка --}}
            <x-button variant="primary">
                Сохранить
            </x-button>

            {{-- Кнопка с иконкой --}}
            <x-button
                variant="success"
                icon="plus-circle"
                iconPosition="start"
            >
                Добавить
            </x-button>

            {{-- Кнопка загрузки --}}
            <x-button
                variant="primary"
                :loading="true"
                loadingText="Сохранение..."
            />

            {{-- Кнопка со счетчиком --}}
            <x-button
                variant="warning"
                icon="shopping-cart"
                badge="3"
            >
                Корзина
            </x-button>

            {{-- Кнопка-ссылка --}}
            <x-button
                variant="outline-primary"
                href="/dashboard"
                icon="arrow-right"
                iconPosition="end"
            >
                Перейти в панель
            </x-button>
            <hr>
            <h2>Группа кнопок</h2>
            <x-button-toolbar aria-label="Панель инструментов">
                <x-button-group class="me-2" aria-label="Форматирование">
                    <x-button variant="outline-secondary" icon="bold"></x-button>
                    <x-button variant="outline-secondary" icon="italic"></x-button>
                    <x-button variant="outline-secondary" icon="underline"></x-button>
                </x-button-group>

                <x-button-group class="me-2" aria-label="Выравнивание">
                    <x-button variant="outline-secondary" icon="bars-3-center-left"></x-button>
                    <x-button variant="outline-secondary" icon="bars-3"></x-button>
                    <x-button variant="outline-secondary" icon="bars-3-bottom-right"></x-button>
                </x-button-group>

                <x-button-group aria-label="Действия">
                    <x-button variant="outline-success" icon="bookmark-square">Сохранить</x-button>
                    <x-button variant="outline-primary" icon="eye">Просмотр</x-button>
                    <x-button variant="outline-danger" icon="trash">Удалить</x-button>
                </x-button-group>
            </x-button-toolbar>
        </div>
    </div>

    <h2>Модальные окна</h2>

    {{-- Кнопка открытия --}}
    <x-button
        data-bs-toggle="modal"
        data-bs-target="#simpleModal"
        variant="primary">
        Открыть модальное окно
    </x-button>

    {{-- Само модальное окно --}}
    <x-modal
        id="simpleModal"
        title="Заголовок модального окна"
        size="lg"
    >
        <p>Содержимое модального окна...</p>

        {{-- Кастомный футер --}}
        @slot('footerContent')
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Закрыть
            </button>
            <button type="button" class="btn btn-success">
                Применить
            </button>
        @endslot
    </x-modal>

    <x-card title="Создание пользователя" class="col-md-8 mx-auto">
        <x-forms.form action="/" method="POST">

            <x-forms.input
                id="tutu"
                name="email"
                label="Email"
                type="email"
                placeholder="user@example.com"
                required
                autofocus
                value="test"
                help="Введите ваш email"
            />

            <x-forms.input
                name="password"
                label="Пароль"
                type="password"
                required
                minlength="6"
            />

            <x-forms.select
                name="role"
                label="Роль"
                :options="[
            'user' => 'Пользователь',
            'admin' => 'Администратор'
        ]"
                placeholder="Выберите роль"
                required
            />

            <x-forms.checkbox
                name="terms"
                label="Согласен с условиями"
                required
                switch
                help="Обязательно для регистрации"
            />

            <div class="mb-3">
                <label class="form-label">Пол</label>

                <x-forms.radio
                    name="gender"
                    value="male"
                    label="Мужской"
                    inline
                />

                <x-forms.radio
                    name="gender"
                    value="female"
                    label="Женский"
                    inline
                />
            </div>

            <x-forms.textarea
                name="bio"
                label="Биография"
                rows="4"
                maxlength="500"
                placeholder="Расскажите о себе"
            />

            <x-forms.file
                name="avatar"
                label="Фото профиля"
                accept="image/*"
                help="JPG или PNG, не более 2MB"
            />

            <button type="submit" class="btn btn-primary">
                Отправить
            </button>
        </x-forms.form>
    </x-card>
@endsection
