<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class CategoryAutocomplete extends Component
{
    /**
     * Создать экземпляр компонента
     */
    public function __construct(
        public string $name = '',
        public string $label = 'Категории',
        public string $placeholder = 'Начните вводить название категории...',
        public string $url = '',
        public Collection|array $selected = [],
        public bool $required = false,
    ) {
        if (!$this->name) {
            throw new \InvalidArgumentException('Поле [name] обязательно для заполнения');
        }

        if (!$this->url) {
            throw new \InvalidArgumentException('Поле [url] обязательно для заполнения');
        }

        if (is_array($this->selected)) {
            $this->selected = collect($this->selected);
        }

        foreach ($this->selected as $item) {
            if ($item instanceof Model && method_exists($item, 'getFullPath')) {
                $item->name = $item->getFullPath();
            }
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
