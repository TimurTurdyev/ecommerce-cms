<?php

namespace App\Tables\Actions;

use Throwable;

class EditAction extends Action
{
    public string $name = 'edit';
    public string $title = 'Редактировать';
    public string $icon = 'pencil-square';

    /**
     * @throws Throwable
     */
    public function render($model): string
    {
        $url = $this->getUrl($model);

        return view('components.button', [
            ...[
                'type' => 'a',
                'size' => 'sm',
                'href' => $url,
                'variant' => $this->style,
                'icon' => $this->icon,
                'title' => $this->title,
                'slot' => !$this->icon ? $this->title : null,
            ], ...$this->attributes,
        ])->render();
    }
}
