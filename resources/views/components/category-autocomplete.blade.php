<div class="mb-3">
    <label class="form-label">
        {{ $label }}
        @if($required)<span class="text-danger">*</span>@endif
    </label>
    <a class="remove-category"><x-main-icon name="minus-circle" size="sm" variant="solid"></x-main-icon></a>
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
                @foreach($selected as $category)
                    <div class="selected-category mb-1 d-inline-block"
                         data-id="{{ $category->id }}"
                         data-name="{{ $category->name }}"
                         data-full-path="{{ $category->getFullPath() }}">
                        <span class="badge bg-primary">
                            <a class="remove-category"><x-main-icon name="minus-circle" size="sm" variant="solid"></x-main-icon></a>
                            {{ $category->getFullPath() }}
                        </span>
                        <input type="hidden" name="{{ $name }}" value="{{ $category->id }}">
                    </div>
                @endforeach
            @else
                <p class="text-muted mb-0 small">Выбранные категории будут отображаться здесь</p>
            @endif
        </div>
    </div>
</div>
