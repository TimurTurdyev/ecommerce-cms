@extends('layouts.app')

@php
    $title = 'Заказы';
    $breadcrumbs = [
        ['label' => 'Главная', 'url' => route('dashboard')],
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
    <div data-table-ajax>
        {{ $tableRenderer->render() }}
    </div>

    @push('scripts')
        @vite(['resources/js/table-ajax.js'])
    @endpush
@endsection
