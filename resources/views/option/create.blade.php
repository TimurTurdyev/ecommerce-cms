@extends('layouts.app')

@php
    $title = $option->id ? 'Редактирование опции' : 'Создание опции';
    $breadcrumbs = [
        ['label' => 'Главная', 'url' => route('dashboard')],
        ['label' => 'Опции', 'url' => route('option.index')],
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
    <x-card>
        <x-slot:header>
            <h5>Основное</h5>
        </x-slot:header>
        <x-forms.form method="post"
                      action="{{ $option->id ? route('option.update', $option) : route('option.store') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-forms.input name="name" label="Название" value="{{ $option->name ?? '' }}" required/>
                </div>
                <div class="col-md-6">
                    <x-forms.select name="type" label="Тип" :options="App\Models\Option::TYPES" :value="$option->type ?? 'select'"/>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="sort_order" label="Сортировка" type="number" value="{{ $option->sort_order ?? 0 }}"/>
                </div>
                <div class="col-md-6">
                    <x-forms.forms.checkbox name="status" label="Включено" checked="{{ $option->status ?? true }}"/>
                </div>
            </div>
            <div class="mt-3">
                <x-button variant="primary" type="submit">Сохранить</x-button>
                <x-button variant="secondary" type="a" href="{{ route('option.index') }}">Отмена</x-button>
            </div>
        </x-form>
    </x-card>

    @if($option->id)
        <x-card class="mt-3">
            <x-slot:header>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Значения опции</h5>
                </div>
            </x-slot:header>
            <form method="post" action="{{ route('option.value.store', $option) }}" class="row g-3 mb-3">
                @csrf
                <div class="col-md-6">
                    <x-forms.input name="name" label="Название" placeholder="Новое значение" required/>
                </div>
                <div class="col-md-4">
                    <x-forms.input name="sort_order" label="Сортировка" type="number" value="0"/>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <x-button variant="primary" type="submit">Добавить</x-button>
                </div>
            </form>

            <table class="table table-bordered table-sm">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Сортировка</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($option->values as $value)
                    <tr>
                        <td>{{ $value->id }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->sort_order }}</td>
                        <td>
                            <form method="post" action="{{ route('option.value.delete', $value) }}">
                                @csrf
                                @method('delete')
                                <x-button variant="outline-danger" size="sm" type="submit">Удалить</x-button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Нет значений</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </x-card>
    @endif
@endsection
