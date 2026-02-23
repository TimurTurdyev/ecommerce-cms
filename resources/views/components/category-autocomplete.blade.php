<div class="mb-3">
    <label class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <div data-category-autocomplete
         data-url="{{ $url }}"
         data-name="{{ $name }}">

        <div class="position-relative">
            <input type="text"
                   class="form-control category-autocomplete-input"
                   placeholder="{{ $placeholder }}"
                   autocomplete="off">
        </div>
        <div class="category-autocomplete-selected mt-2 p-3 border rounded bg-light"
             data-placeholder="Выбранные категории будут отображаться здесь">
            @if($selected->isNotEmpty())
                @foreach($selected as $item)
                    <div class="selected-category"
                         data-id="{{ $item->id }}"
                         data-name="{{ $item->name }}">
                        <a class="remove-category">
                            <x-main-icon name="minus-circle" size="sm" variant="solid"></x-main-icon>
                        </a>
                        [{{ $item->id }}] {{ $item->name }}
                        <input type="hidden" name="{{ $name }}" value="{{ $item->id }}">
                    </div>
                @endforeach
            @else
                <p class="text-muted mb-0 small">Выбранные категории будут отображаться здесь</p>
            @endif
        </div>
    </div>
</div>
