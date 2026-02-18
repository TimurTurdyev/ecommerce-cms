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
        <x-forms.form method="post"
                      action="{{ $category->id ? route('category.update', $category) : route('category.store') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-forms.input name="name" label="Название" value="{{ $category->name ?? '' }}" required/>
                </div>
                <div class="col-md-6">
                    <x-forms.select name="parent_id" label="Родительская категория" :value="$category->parent_id ?? 0">
                        <option value="0">-- Нет --</option>
                        @foreach($parents as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="sort_order" label="Сортировка" type="number" value="{{ $category->sort_order ?? 0 }}"/>
                </div>
                <div class="col-md-6">
                    <x-forms.checkbox name="status" label="Включено" checked="{{ $category->status ?? true }}"/>
                </div>
                <div class="col-12">
                    <x-forms.textarea name="description" label="Описание">{{ $category->description ?? '' }}</x-forms.textarea>
                </div>
                <div class="col-12">
                    <hr>
                    <h5>SEO</h5>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="meta_title" label="Meta Title" value="{{ $category->meta_title ?? '' }}"/>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="meta_h1" label="Meta H1" value="{{ $category->meta_h1 ?? '' }}"/>
                </div>
                <div class="col-12">
                    <x-forms.textarea name="meta_description" label="Meta Description">{{ $category->meta_description ?? '' }}</x-forms.textarea>
                </div>
                <div class="col-12">
                    <x-forms.textarea name="meta_keyword" label="Meta Keywords">{{ $category->meta_keyword ?? '' }}</x-forms.textarea>
                </div>
            </div>
            <div class="mt-3">
                <x-button variant="primary" type="submit">Сохранить</x-button>
                <x-button variant="secondary" type="a" href="{{ route('category.index') }}">Отмена</x-button>
            </div>
        </x-form>
    </x-card>
@endsection
