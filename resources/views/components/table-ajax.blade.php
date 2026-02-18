@props(['config', 'items', 'request'])

@php
    /** @var Illuminate\Pagination\LengthAwarePaginator $items */
    /** @var App\Tables\Configuration\TableConfiguration $config */
@endphp
<div class="card">
    <div class="card-header">
        {{-- Фильтры (можно вынести в отдельный компонент) --}}
        @if(count($config->getFilters()))
            <div class="row mb-3">
                <div class="col-md-12">
                    <form method="GET" class="filter-form row g-3 align-items-end" autocomplete="off">
                        @foreach($config->getFilters() as $filter)
                            @if($filter->isVisible())
                                {!! $filter->render() !!}
                            @endif
                        @endforeach
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">Применить</button>
                            <button type="button" class="reset-filters btn btn-sm btn-secondary">Сбросить</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
        {{-- Поиск --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text"
                       class="search-input form-control form-control-sm"
                       placeholder="Поиск..."
                       value="{{ $request->input('search') }}">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            {{-- Таблица --}}
            <table class="table table-striped table-hover table-sm">
                <thead>
                <tr>
                    @foreach($config->getColumns() as $column)
                        @if($column->isVisible())
                            <th>
                                {{ $column->title }}
                                @if($column->orderable)
                                    <span class="sortable" data-sort="{{ $column->data }}" style="cursor:pointer;">
                                    @if($request->input('sort') == $column->data)
                                            {{ $request->input('direction') == 'asc' ? '↑' : '↓' }}
                                        @else
                                            ⇅
                                        @endif
                                </span>
                                @endif
                            </th>
                        @endif
                    @endforeach
                    @if(count($config->getActions()))
                        <th>Действия</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @forelse($items as $model)
                    <tr>
                        @foreach($config->getColumns() as $column)
                            @if($column->isVisible())
                                <td>
                                    @if($column->format)
                                        {{ call_user_func($column->format, $model) }}
                                    @else
                                        {{ $model->{$column->data} }}
                                    @endif
                                </td>
                            @endif
                        @endforeach
                        @if(count($config->getActions()))
                            <td>
                                @foreach($config->getActions() as $action)
                                    @php
                                        /** @var App\Tables\Actions\Action $action */
                                    @endphp
                                    @if($action->isVisible() && $action->isAuthorized($model))
                                        {!! $action->render($model) !!}
                                    @endif
                                @endforeach
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center">Нет данных</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($items->hasPages())
        <div class="table-footer p-2">
            {{ $items->appends($request->except('page'))->links() }}
        </div>
    @endif
</div>
