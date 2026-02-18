<?php

namespace App\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;

class TextFilter extends Filter
{
    private string $attribute;

    private string $operator = 'like'; // можно '=', 'like', etc.

    private string $type = 'text';

    public function __construct(string $key, string $label, string $attribute)
    {
        parent::__construct($key, $label);
        $this->attribute = $attribute;
    }

    public function operator(string $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function apply(Builder $query, $value): Builder
    {
        if ($this->hasNullMode()) {
            return $this->applyNullMode($query, $this->attribute);
        }

        if ($value === '' || $value === null) {
            return $query;
        }

        if ($this->operator === 'like') {
            return $query->where($this->attribute, 'LIKE', "%{$value}%");
        }

        return $query->where($this->attribute, $this->operator, $value);
    }

    public function render(): string
    {
        $value = htmlspecialchars($this->value ?? '');

        $inputHtml = '<div class="col-auto">';

        if ($this->label) {
            $inputHtml .= '<label class="form-label">'.$this->label.'</label>';
        }

        $inputHtml .= <<<HTML
<input type="{$this->type}"
    name="{$this->key}"
    value="{$value}"
    placeholder="{$this->label}"
    class="filter-input form-control form-control-sm">
</div>
HTML;

        return $inputHtml;
    }
}
