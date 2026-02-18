@props([
    'actions' => [],
    'row' => [],
    'actionCallable' => null,
])

@php
    if (empty($actions)) {
        // Действия по умолчанию
        $actions = [
            [
                'type' => 'view',
                'url' => $row['view_url'] ?? '#',
                'icon' => 'eye',
                'title' => 'Просмотр',
                'actionCallable' => $actionCallable,
            ],
            [
                'type' => 'edit',
                'url' => $row['edit_url'] ?? '#',
                'icon' => 'pencil',
                'title' => 'Редактировать',
                'actionCallable' => $actionCallable,
            ],
            [
                'type' => 'delete',
                'url' => $row['delete_url'] ?? '#',
                'icon' => 'trash',
                'title' => 'Удалить',
                'confirm' => true,
                'method' => 'DELETE',
                'actionCallable' => $actionCallable,
            ],
        ];
    }
    $formDelete = false;
@endphp

<div class="table-actions">
    <div class="btn-group btn-group-sm">
        @foreach($actions as $action)
            @php
                if ($action['actionCallable'] ?? null) {
                    $action['actionCallable']($row, $action);
                    if (!$action) {
                        continue;
                    }
                }
                $type = $action['type'] ?? 'button';
                $variant = $action['variant'] ?? 'outline-secondary';
                $icon = $action['icon'] ?? null;
                $title = $action['title'] ?? '';
                $url = $action['url'] ?? '#';
                $method = $action['method'] ?? 'GET';
                $confirm = $action['confirm'] ?? false;
                $confirmText = $action['confirm_text'] ?? 'Вы уверены?';
                $disabled = $action['disabled'] ?? false;
                $dropdown = $action['dropdown'] ?? false;
                if (is_callable($url)) {
                    $url = $url($row);
                }
            @endphp

            @if($dropdown)
                <div class="dropdown">
                    <button class="btn btn-{{ $variant }} dropdown-toggle btn-sm" type="button"
                            data-bs-toggle="dropdown">
                        @if($action['icon'] ?? false)
                            <x-main-icon :name="$action['icon']" set="heroicon" size="sm" class="me-2"/>
                        @endif
                        {{ $action['label'] ?? '' }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        @foreach($dropdown as $item)
                            @php
                                if (is_callable($item['url'])) {
                                     $item['url'] = $item['url']($row);
                                }
                            @endphp
                            <a
                                class="dropdown-item"
                                href="{{ $item['url'] ?? '#' }}"
                                @if($item['confirm'] ?? false)
                                    onclick="return confirm('{{ $item['confirm_text'] ?? 'Вы уверены?' }}')"
                                @endif
                            >
                                @if($item['icon'] ?? false)
                                    <x-main-icon :name="$item['icon']" set="heroicon" size="sm" class="me-2"/>
                                @endif
                                {{ $item['label'] ?? '' }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @elseif($type === 'delete' || $confirm)
                @php
                    $formDelete = $url;
                @endphp
                <button
                    type="submit"
                    class="btn btn-{{ $variant }}"
                    title="{{ $title }}"
                    form="form-delete-{{ $row['id'] }}"
                    data-url="{{ $url }}"
                    data-method="post"
                    onclick="return confirm('{{ $confirmText }}');"
                    @if($disabled) disabled @endif
                >
                    @if($icon)
                        <x-main-icon :name="$icon" set="heroicon" size="sm"/>
                    @endif
                </button>
            @else
                <a
                    href="{{ $url }}"
                    class="btn btn-{{ $variant }}"
                    title="{{ $title }}"
                    @if($disabled) disabled @endif
                >
                    @if($icon)
                        <x-main-icon :name="$icon" set="heroicon" size="sm"/>
                    @else
                        {{ $title }}
                    @endif
                </a>
            @endif
        @endforeach
    </div>
    @if($formDelete)
        <x-forms.form method="delete" id="form-delete-{{ $row['id'] }}" action="{{ $formDelete }}"></x-forms.form>
    @endif
</div>
