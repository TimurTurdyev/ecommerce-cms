@props([
    'header' => [],
    'value' => null,
    'row' => [],
    'type' => 'text',
    'align' => null,
    'class' => '',
    'selectable' => false,
    'rowId' => null,
])

@php
    $cellClasses = '';
    if ($class) $cellClasses .= ' ' . $class;

    $cellStyles = '';
    if ($align) $cellStyles .= 'text-align: ' . $align . ';';

    // Определяем содержимое ячейки в зависимости от типа
    $content = '';

    switch($type) {
        case 'checkbox':
            $content = '
                <div class="form-check">
                    <input
                        type="checkbox"
                        class="form-check-input table-row-select"
                        data-id="' . $rowId . '"
                        id="row-' . $rowId . '"
                    >
                </div>
            ';
            break;

        case 'badge':
            $color = $row['_badge_color'] ?? 'primary';
            $content = '
                <x-badge :type="$color" pill>
                    ' . e($value) . '
                </x-badge>
            ';
            break;

        case 'status':
            $statusColors = [
                'active' => 'success',
                'inactive' => 'secondary',
                'pending' => 'warning',
                'blocked' => 'danger',
                'completed' => 'success',
                'failed' => 'danger',
            ];
            $color = $statusColors[strtolower($value)] ?? 'secondary';
            $content = '
                <span class="status-indicator">
                    <span class="status-dot bg-' . $color . '"></span>
                    ' . e($value) . '
                </span>
            ';
            break;

        case 'avatar':
            $name = $row['name'] ?? '';
            $email = $row['email'] ?? '';
            $avatar = $row['avatar'] ?? null;

            $content = '
                <div class="d-flex align-items-center">
                    ' . ($avatar ? '
                        <img src="' . e($avatar) . '" alt="' . e($name) . '" class="avatar-sm rounded-circle me-2">
                    ' : '
                        <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2">
                            ' . substr($name ?: $email, 0, 1) . '
                        </div>
                    ') . '
                    <div>
                        <div class="fw-medium">' . e($name) . '</div>
                        <div class="text-muted small">' . e($email) . '</div>
                    </div>
                </div>
            ';
            break;

        case 'progress':
            $percentage = min(100, max(0, (int)$value));
            $color = $percentage >= 80 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger');
            $content = '
                <div class="progress-wrapper">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">' . $percentage . '%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-' . $color . '" role="progressbar" style="width: ' . $percentage . '%"></div>
                    </div>
                </div>
            ';
            break;

        case 'rating':
            $max = $header['max'] ?? 5;
            $stars = '';
            $value = min($max, max(0, (int)$value));

            for ($i = 1; $i <= $max; $i++) {
                $stars .= '<i class="bi bi-star' . ($i <= $value ? '-fill' : '') . ' text-warning"></i>';
            }

            $content = '<div class="rating-stars">' . $stars . '</div>';
            break;

        case 'actions':
            $actions = $row['_actions'] ?? $header['_actions'] ?? null;
            $actionCallable = $row['action_callable'] ?? $header['action_callable'] ?? null;

            $content = view('components.table.actions', [
                'actions' => $actions,
                'row' => $row,
                'actionCallable' => $actionCallable,
            ])->render();
            break;

        case 'boolean':
            $content = $value ? '
                <x-main-icon name="check-circle" set="heroicon" class="text-success" />
            ' : '
                <x-main-icon name="x-circle" set="heroicon" class="text-danger" />
            ';
            break;

        case 'image':
            $content = $value ? '
                <img src="' . e($value) . '" alt="" class="table-img" style="max-height: 40px;">
            ' : '—';
            break;

        case 'custom':
            $content = $value;
            break;

        default:
            $content = e($value) ?: '—';
    }
@endphp

<td
    class="{{ trim($cellClasses) }}"
    style="{{ $cellStyles }}"
    data-column="{{ $header['key'] ?? '' }}"
>
    {!! $content !!}
</td>
