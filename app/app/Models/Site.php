<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [
        'subdomain','domain','name','locale','timezone','theme','modules','is_active'
    ];

    protected $casts = [
        'modules' => 'array',
        'is_active' => 'boolean',
    ];
}
