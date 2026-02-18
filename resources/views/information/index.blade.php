@extends('layouts.app')

@php
    $title = 'Статьи';
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
    >
        <x-slot:actions>
            <x-button variant="primary" type="a" href="{{ route('information.create') }}" icon="plus">Создать</x-button>
        </x-slot:actions>
    </x-page-header>
@endsection

@section('content')
    <div data-table-ajax>
        {{ $tableRenderer->render() }}
    </div>

    @push('scripts')
        @vite(['resources/js/table-ajax.js'])
    @endpush
@endsection
