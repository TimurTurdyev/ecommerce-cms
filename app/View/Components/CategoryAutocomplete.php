<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class CategoryAutocomplete extends Component
{
    /**
     * Создать экземпляр компонента
     */
    public function __construct(
        public string $name = 'product_categories[]',
        public string $label = 'Категории',
        public string $placeholder = 'Начните вводить название категории...',
        public string $url = '/category/search',
        public Collection|array $selected = [],
        public bool $required = false,
    ) {
        if (is_array($this->selected)) {
            $this->selected = collect($this->selected);
        }
    }

    /**
     * Получить view компонента
     */
    public function render(): View
    {
        return view('components.category-autocomplete');
    }
}
