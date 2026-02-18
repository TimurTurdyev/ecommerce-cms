<?php

namespace App\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;

class SelectFilter extends Filter
{
    public array $options;

    public string $attribute;

    public bool $multiple = false;

    public string $placeholder = 'Все';

    public function __construct(string $key, string $label, string $attribute, array $options)
    {
        parent::__construct($key, $label);
        $this->attribute = $attribute;
        $this->options = $options;
    }

    public function multiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function apply(Builder $query, $value): Builder
    {
        if (!$this->attribute) {
            return $query;
        }

        if ($this->hasNullMode()) {
            return $this->applyNullMode($query, $this->attribute);
        }

        if ($value === '' || $value === null) {
            return $query;
        }

        if ($this->multiple && is_array($value)) {
            return $query->whereIn($this->attribute, $value);
        }

        return $query->where($this->attribute, $value);
    }

    public function render(): string
    {
        $name = $this->multiple ? $this->key.'[]' : $this->key;
        $multipleAttr = $this->multiple ? ' multiple' : '';

        $selectHtml = '<div class="col-auto">';
        $optionsHtml = '';

        if ($this->label) {
            $selectHtml .= '<label class="form-label">'.$this->label.'</label>';
        }

        foreach ($this->options as $optValue => $optLabel) {
            $selected = $this->isSelected($optValue) ? ' selected' : '';
            $optionsHtml .= "<option value=\"{$optValue}\"{$selected}>{$optLabel}</option>";
        }

        $placeholder = $this->placeholder ?? '';

        if ($placeholder) {
            $placeholder .= "<option value=\"\">{$placeholder}</option>";
        }

        $selectHtml .= <<<HTML
<select
    name="$name"
    class="form-select form-select-sm filter-select"$multipleAttr>
    $placeholder
    $optionsHtml
</select>
</div>
HTML;

        return $selectHtml;
    }

    protected function isSelected($optionValue): bool
    {
        if ($this->multiple && is_array($this->value)) {
            return in_array($optionValue, $this->value);
        }

        return $this->value == $optionValue;
    }
}
