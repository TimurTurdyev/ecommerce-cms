<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
