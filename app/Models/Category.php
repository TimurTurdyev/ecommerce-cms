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
        // Verbose logging
        if (config('app.debug') && env('LOG_LEVEL') === 'debug') {
            Log::debug('[Category.getFullPath] Getting full path for category', [
                'category_id' => $this->id,
                'category_name' => $this->name,
                'parent_id' => $this->parent_id,
            ]);
        }

        if ($this->parent_id && $parent = $this->parent) {
            $path = $parent->getFullPath() . ' > ' . $this->name;

            if (config('app.debug') && env('LOG_LEVEL') === 'debug') {
                Log::debug('[Category.getFullPath] Recursive call result', [
                    'category_id' => $this->id,
                    'full_path' => $path,
                ]);
            }

            return $path;
        }

        if (config('app.debug') && env('LOG_LEVEL') === 'debug') {
            Log::debug('[Category.getFullPath] Root category (no parent)', [
                'category_id' => $this->id,
                'name' => $this->name,
            ]);
        }

        return $this->name;
    }

    /**
     * Scope для поиска категорий по имени
     * Case-insensitive, только активные категории
     */
    public function scopeSearch($query, $term)
    {
        // Verbose logging
        if (config('app.debug') && env('LOG_LEVEL') === 'debug') {
            Log::debug('[Category.scopeSearch] Searching categories', [
                'search_term' => $term,
            ]);
        }

        $results = $query->where('name', 'LIKE', "%{$term}%")
            ->where('status', true);

        if (config('app.debug') && env('LOG_LEVEL') === 'debug') {
            $count = $results->count();
            Log::debug('[Category.scopeSearch] Search results', [
                'search_term' => $term,
                'results_count' => $count,
            ]);
        }

        return $results;
    }
}
