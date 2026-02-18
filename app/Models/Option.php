<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public const TYPE_SELECT = 'select';

    const TYPE_RADIO = 'radio';

    const TYPE_CHECKBOX = 'checkbox';

    const TYPE_TEXT = 'text';

    const TYPE_TEXTAREA = 'textarea';

    public const TYPES = [
        self::TYPE_SELECT => 'Выбор из списка',
        self::TYPE_RADIO => 'Радиокнопки',
        self::TYPE_CHECKBOX => 'Чекбоксы',
        self::TYPE_TEXT => 'Текстовое поле',
        self::TYPE_TEXTAREA => 'Текстовая область',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(OptionValue::class)->orderBy('sort_order');
    }
}
