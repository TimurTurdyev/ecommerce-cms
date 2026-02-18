@extends('layouts.app')

@php
    $title = $product->id ? 'Редактирование товара' : 'Создание товара';
    $breadcrumbs = [
        ['label' => 'Главная', 'url' => route('dashboard')],
        ['label' => 'Товары', 'url' => route('product.index')],
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
        <x-forms.form method="{{ $product->id ? 'put' : 'post' }}"
                      action="{{ $product->id ? route('product.update', $product) : route('product.store') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-forms.input name="name" label="Название"
                                   value="{{ old('name', $product->name ?? '') }}" required/>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="model" label="Модель" value="{{ old('model', $product->model ?? '') }}"
                                   required/>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="sku" label="Артикул" value="{{ old('sku', $product->sku ?? '') }}"/>
                </div>
                <div class="col-md-6">
                    <x-forms.select name="manufacturer_id" label="Производитель">
                        <option value="">-- Не выбран --</option>
                        @foreach($manufacturers as $id => $name)
                            <option
                                value="{{ $id }}" {{ old('manufacturer_id', $product->manufacturer_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
                <div class="col-md-4">
                    <x-forms.input name="price" label="Цена" type="number" step="0.01"
                                   value="{{ old('price', $product->price ?? 0) }}"/>
                </div>
                <div class="col-md-4">
                    <x-forms.input name="quantity" label="Количество" type="number"
                                   value="{{ old('quantity', $product->quantity ?? 0) }}"/>
                </div>
                <div class="col-md-4">
                    <x-forms.input name="sort_order" label="Сортировка" type="number"
                                   value="{{ old('sort_order', $product->sort_order ?? 0) }}"/>
                </div>
                <div class="col-md-3">
                    <x-forms.input name="weight" label="Вес" type="number" step="0.01"
                                   value="{{ old('weight', $product->weight ?? 0) }}"/>
                </div>
                <div class="col-md-3">
                    <x-forms.input name="length" label="Длина" type="number" step="0.01"
                                   value="{{ old('length', $product->length ?? 0) }}"/>
                </div>
                <div class="col-md-3">
                    <x-forms.input name="width" label="Ширина" type="number" step="0.01"
                                   value="{{ old('width', $product->width ?? 0) }}"/>
                </div>
                <div class="col-md-3">
                    <x-forms.input name="height" label="Высота" type="number" step="0.01"
                                   value="{{ old('height', $product->height ?? 0) }}"/>
                </div>
                <div class="col-md-6">
                    <x-forms.checkbox name="status" label="Включено"
                                      checked="{{ old('status', $product->status ?? true) }}"/>
                </div>
                <div class="col-12">
                    <x-forms.textarea name="description"
                                      label="Описание">{{ old('description', $product->description ?? '') }}</x-forms.textarea>
                </div>
                <div class="col-12">
                    <x-forms.input name="tag" label="Теги" value="{{ old('tag', $product->tag ?? '') }}"/>
                </div>
                <div class="col-12">
                    <hr>
                    <h5>SEO</h5>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="meta_title" label="Meta Title"
                                   value="{{ old('meta_title', $product->meta_title ?? '') }}"/>
                </div>
                <div class="col-md-6">
                    <x-forms.input name="meta_h1" label="Meta H1"
                                   value="{{ old('meta_h1', $product->meta_h1 ?? '') }}"/>
                </div>
                <div class="col-12">
                    <x-forms.textarea name="meta_description"
                                      label="Meta Description">{{ old('meta_description', $product->meta_description ?? '') }}</x-forms.textarea>
                </div>
                <div class="col-12">
                    <x-forms.textarea name="meta_keyword"
                                      label="Meta Keywords">{{ old('meta_keyword', $product->meta_keyword ?? '') }}</x-forms.textarea>
                </div>
            </div>
            <div class="mt-3">
                <x-button variant="primary" type="submit">Сохранить</x-button>
                <x-button variant="secondary" type="a" href="{{ route('product.index') }}">Отмена</x-button>
            </div>
        </x-forms.form>
    </x-card>
@endsection
