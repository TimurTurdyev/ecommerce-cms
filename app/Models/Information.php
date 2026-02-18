<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $fillable = [
        'bottom',
        'sort_order',
        'status',
        'title',
        'description',
        'meta_title',
        'meta_h1',
        'meta_description',
        'meta_keyword',
    ];

    protected $casts = [
        'status' => 'boolean',
        'bottom' => 'boolean',
    ];
}
