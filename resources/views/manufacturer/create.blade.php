@extends('layouts.app')

@php
    $title = $manufacturer->id ? 'Редактирование производителя' : 'Создание производителя';
    $breadcrumbs = [
        ['label' => 'Главная', 'url' => route('dashboard')],
        ['label' => 'Производители', 'url' => route('manufacturer.index')],
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
        <x-forms.form method="{{ $manufacturer->id ? 'put' : 'post' }}"
                      action="{{ $manufacturer->id ? route('manufacturer.update', $manufacturer) : route('manufacturer.store') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-forms.input name="name" label="Название" value="{{ old('name', $manufacturer->name ?? '') }}"
                                   required/>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="sort_order" label="Сортировка" type="number"
                                   value="{{ old('sort_order', $manufacturer->sort_order ?? 0) }}"/>
                </div>
                <div class="col-md-6">
                    <x-forms.checkbox name="status" label="Включено"
                                      checked="{{ old('status', $manufacturer->status ?? true) }}"/>
                </div>
            </div>
            <div class="mt-3">
                <x-button variant="primary" type="submit">Сохранить</x-button>
                <x-button variant="secondary" type="a" href="{{ route('manufacturer.index') }}">Отмена</x-button>
            </div>
        </x-forms.form>
    </x-card>
@endsection
