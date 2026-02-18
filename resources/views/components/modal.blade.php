@props([
    'id' => 'modal-' . Str::random(10),
    'title' => null,
    'size' => null,
    'centered' => true,
    'scrollable' => false,
    'staticBackdrop' => false,
    'fade' => true,
    'closeButton' => true,
    'header' => true,
    'footer' => true,
    'footerContent' => null,
    'submitText' => 'Сохранить',
    'submitIcon' => null,
    'submitIconSet' => 'heroicon',
    'cancelText' => 'Отмена',
    'cancelIcon' => null,
    'cancelIconSet' => 'heroicon',
    'static' => false,
    'icon' => null,
    'iconSet' => 'heroicon',
    'iconVariant' => 'outline',
])

@php
    $modalClasses = 'modal';
    if ($fade) $modalClasses .= ' fade';

    $dialogClasses = 'modal-dialog';
    if ($centered) $dialogClasses .= ' modal-dialog-centered';
    if ($scrollable) $dialogClasses .= ' modal-dialog-scrollable';
    if ($static) $dialogClasses .= ' modal-dialog-static';

    if ($size) {
        if (in_array($size, ['sm', 'lg', 'xl'])) {
            $dialogClasses .= ' modal-' . $size;
        } elseif ($size === 'fullscreen') {
            $dialogClasses .= ' modal-fullscreen';
        }
    }
@endphp

<div
    class="{{ $modalClasses }}"
    id="{{ $id }}"
    tabindex="-1"
    aria-labelledby="{{ $id }}-label"
    aria-hidden="true"
    @if($staticBackdrop) data-bs-backdrop="static" @endif
    @if($static) data-bs-keyboard="false" @endif
    {{ $attributes }}
>
    <div class="{{ $dialogClasses }}">
        <div class="modal-content">
            {{-- Header --}}
            @if($header)
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2">
                        @if($icon)
                            @if($iconSet === 'heroicon')
                                <x-main-icon
                                    :name="$icon"
                                    set="heroicon"
                                    :variant="$iconVariant"
                                    size="lg"
                                    class="text-primary"
                                />
                            @else
                                <i class="bi bi-{{ $icon }} text-primary fs-4"></i>
                            @endif
                        @endif

                        @if($title)
                            <h5 class="modal-title" id="{{ $id }}-label">
                                {{ $title }}
                            </h5>
                        @else
                            {{ $headerContent ?? '' }}
                        @endif
                    </div>

                    @if($closeButton)
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    @endif
                </div>
            @endif

            {{-- Body --}}
            <div class="modal-body">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            @if($footer)
                <div class="modal-footer">
                    @if($footerContent)
                        {!! $footerContent !!}
                    @else
                        <button
                            type="button"
                            class="btn btn-secondary d-inline-flex align-items-center gap-1"
                            data-bs-dismiss="modal"
                        >
                            @if($cancelIcon)
                                @if($cancelIconSet === 'heroicon')
                                    <x-main-icon
                                        :name="$cancelIcon"
                                        set="heroicon"
                                        variant="outline"
                                        size="sm"
                                    />
                                @else
                                    <i class="bi bi-{{ $cancelIcon }}"></i>
                                @endif
                            @endif
                            {{ $cancelText }}
                        </button>

                        <button
                            type="submit"
                            form="{{ $attributes->get('form') }}"
                            class="btn btn-primary d-inline-flex align-items-center gap-1"
                        >
                            @if($submitIcon)
                                @if($submitIconSet === 'heroicon')
                                    <x-main-icon
                                        :name="$submitIcon"
                                        set="heroicon"
                                        variant="outline"
                                        size="sm"
                                    />
                                @else
                                    <i class="bi bi-{{ $submitIcon }}"></i>
                                @endif
                            @endif
                            {{ $submitText }}
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
