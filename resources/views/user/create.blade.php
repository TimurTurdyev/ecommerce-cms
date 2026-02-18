@extends('layouts.app')

@php
    if ($user->exists) {
        $title = 'Редактировать пользователя';
        $actionRoute =  route('user.update', $user);
    } else {
        $title = 'Создать пользователя';
        $actionRoute =  route('user.store');
    }

    $breadcrumbs = [
        ['label' => 'Главная', 'url' => route('dashboard')],
        ['label' => 'Пользователи', 'url' => route('user.index')],
        ['label' => $title],
    ];
@endphp

@section('title', $title)

@section('page-header')
    <x-page-header
        :title="$title"
        :breadcrumbs="$breadcrumbs"
    >
        <x-slot:actions>
            <x-button variant="outline-secondary" icon="arrow-left" href="{{route('user.index')}}">
                Назад
            </x-button>
            <x-button variant="primary" type="submit" form="user-form" icon="plus">Сохранить</x-button>
        </x-slot:actions>
    </x-page-header>
@endsection

@section('content')
    <x-card>
        <x-forms.form id="user-form" action="{{ $actionRoute }}" method="POST">
            <x-forms.input name="name" :value="$user->name" label="Имя"></x-forms.input>
            <x-forms.input name="email" :value="$user->email" label="Почта"></x-forms.input>
            <x-forms.select
                :options="$roles"
                :selected="$user->role"
                placeholder="--"
                name="role"
                label="Роль"
            ></x-forms.select>
        </x-forms.form>
    </x-card>
@endsection
