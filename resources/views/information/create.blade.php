@extends('layouts.app')

@php
    $title = $information->id ? 'Редактирование статьи' : 'Создание статьи';
    $breadcrumbs = [
        ['label' => 'Главная', 'url' => route('dashboard')],
        ['label' => 'Статьи', 'url' => route('information.index')],
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
        <x-forms.form method="post"
                      action="{{ $information->id ? route('information.update', $information) : route('information.store') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-forms.input name="title" label="Название"
                                   value="{{ old('title', $information->title ?? '') }}" required/>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="sort_order" label="Сортировка" type="number"
                                   value="{{ old('sort_order', $information->sort_order ?? 0) }}"/>
                </div>
                <div class="col-md-6">
                    <x-forms.checkbox name="status" label="Включено"
                                      checked="{{ old('status', $information->status ?? true) }}"/>
                </div>
                <div class="col-md-6">
                    <x-forms.checkbox name="bottom" label="Показывать в подвале"
                                      checked="{{ old('bottom', $information->bottom ?? false) }}"/>
                </div>
                <div class="col-12">
                    <x-forms.textarea name="description"
                                      label="Описание">{{ old('description', $information->description ?? '') }}</x-forms.textarea>
                </div>
                <div class="col-12">
                    <hr>
                    <h5>SEO</h5>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="meta_title" label="Meta Title"
                                   value="{{ old('meta_title', $information->meta_title ?? '') }}"/>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="meta_h1" label="Meta H1"
                                   value="{{ old('meta_h1', $information->meta_h1 ?? '') }}"/>
                </div>
                <div class="col-12">
                    <x-forms.textarea name="meta_description"
                                      label="Meta Description">{{ old('meta_description', $information->meta_description ?? '') }}</x-forms.textarea>
                </div>
                <div class="col-12">
                    <x-forms.textarea name="meta_keyword"
                                      label="Meta Keywords">{{ old('meta_keyword', $information->meta_keyword ?? '') }}</x-forms.textarea>
                </div>
            </div>
            <div class="mt-3">
                <x-button variant="primary" type="submit">Сохранить</x-button>
                <x-button variant="secondary" type="a" href="{{ route('information.index') }}">Отмена</x-button>
            </div>
        </x-forms.form>
    </x-card>
@endsection
