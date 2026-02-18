@extends('layouts.app')

@php
    $title = $category->id ? 'Редактирование категории' : 'Создание категории';
    $breadcrumbs = [
        ['label' => 'Главная', 'url' => route('dashboard')],
        ['label' => 'Категории', 'url' => route('category.index')],
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
        <x-forms.form method="{{ $category->id ? 'put' : 'post' }}"
                      action="{{ $category->id ? route('category.update', $category) : route('category.store') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-forms.input name="name" label="Название"
                                   value="{{ old('name', $category->name ?? '') }}" required/>
                </div>
                <div class="col-md-6">
                    <x-forms.select name="parent_id" label="Родительская категория">
                        <option value="0">-- Нет --</option>
                        @foreach($parents as $id => $name)
                            <option
                                value="{{ $id }}" {{ old('parent_id', $category->parent_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="sort_order" label="Сортировка" type="number"
                                   value="{{ old('sort_order', $category->sort_order ?? 0) }}"/>
                </div>
                <div class="col-md-6">
                    <x-forms.checkbox name="status" label="Включено"
                                      checked="{{ old('status', $category->status ?? true) }}"/>
                </div>
                <div class="col-12">
                    <x-forms.textarea name="description"
                                      label="Описание">{{ old('description', $category->description ?? '') }}</x-forms.textarea>
                </div>
                <div class="col-12">
                    <hr>
                    <h5>SEO</h5>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="meta_title" label="Meta Title"
                                   value="{{ old('meta_title', $category->meta_title ?? '') }}"/>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="meta_h1" label="Meta H1"
                                   value="{{ old('meta_h1', $category->meta_h1 ?? '') }}"/>
                </div>
                <div class="col-12">
                    <x-forms.textarea name="meta_description"
                                      label="Meta Description">{{ old('meta_description', $category->meta_description ?? '') }}</x-forms.textarea>
                </div>
                <div class="col-12">
                    <x-forms.textarea name="meta_keyword"
                                      label="Meta Keywords">{{ old('meta_keyword', $category->meta_keyword ?? '') }}</x-forms.textarea>
                </div>
            </div>
            <div class="mt-3">
                <x-button variant="primary" type="submit">Сохранить</x-button>
                <x-button variant="secondary" type="a" href="{{ route('category.index') }}">Отмена</x-button>
            </div>
        </x-forms.form>
    </x-card>
@endsection
