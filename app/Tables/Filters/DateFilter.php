<?php

namespace App\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;

class DateFilter extends Filter
{
    private string $attribute;

    public function __construct(string $key, string $label, string $attribute)
    {
        parent::__construct($key, $label);
        $this->attribute = $attribute;
    }

    public function apply(Builder $query, $value): Builder
    {
        if ($this->hasNullMode()) {
            return $this->applyNullMode($query, $this->attribute);
        }

        if (!is_array($value) || !isset($value['from'])) {
            return $query;
        }

        if (!isset($value['to'])) {
            $value['to'] = now()->format('Y-m-d');
        }

        return $query->whereBetween($this->attribute, $value);
    }

    public function render(): string
    {
        $value1 = $this->value['from'] ?? '';
        $value2 = $this->value['to'] ?? '';

        $inputHtml = '<div class="col-auto">';

        if ($this->label) {
            $inputHtml .= '<label class="form-label">'.$this->label.'</label>';
        }

        $inputHtml .= <<<HTML

<div class="input-group input-group-sm">
  <input type="date"
    name="{$this->key}[from]"
    value="{$value1}"
    class="filter-input form-control">
  <span class="input-group-text">-</span>
  <input type="date"
    name="{$this->key}[to]"
    value="{$value2}"
    class="form-control">
</div>
</div>
HTML;

        return $inputHtml;
    }
}
