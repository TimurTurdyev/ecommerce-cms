<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'image',
        'sort_order',
        'status',
        'name',
        'description',
        'meta_title',
        'meta_h1',
        'meta_description',
        'meta_keyword',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relationship: parent category
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relationship: children categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Получить полный путь категории с учетом иерархии
     * Формат: "Родитель > Подкатегория > Текущая"
     */
    public function getFullPath(): string
    {
        if ($this->parent_id && $parent = $this->parent) {
            $path = $parent->getFullPath() . ' > ' . $this->name;

            return $path;
        }

        return $this->name;
    }

    /**
     * Scope для поиска категорий по имени
     * Case-insensitive, только активные категории
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%")
            ->where('status', true);
    }
}
