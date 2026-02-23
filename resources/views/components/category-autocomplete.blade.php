<div class="mb-3">
    <label class="form-label">
        {{ $label }}
        @if($required)<span class="text-danger">*</span>@endif
    </label>

    <div data-category-autocomplete
         data-url="{{ $url }}"
         data-name="{{ $name }}">

        <input type="text"
               class="form-control category-autocomplete-input"
               placeholder="{{ $placeholder }}"
               autocomplete="off">

        <div class="category-autocomplete-selected mt-2 p-3 border rounded bg-light"
             data-placeholder="Выбранные категории будут отображаться здесь">
            @if($selected->isNotEmpty())
                @foreach($selected as $category)
                    <div class="selected-category mb-1 d-inline-block"
                         data-id="{{ $category->id }}"
                         data-name="{{ $category->name }}"
                         data-full-path="{{ $category->getFullPath() }}">
                        <span class="badge bg-primary">
                            <i class="fa fa-times-circle remove-category" style="cursor: pointer;"></i>
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
