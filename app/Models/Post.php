<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $casts = [
        'publish_at' => 'datetime',
        'publish_until' => 'datetime',
        'is_published' => 'boolean'
    ];
}
