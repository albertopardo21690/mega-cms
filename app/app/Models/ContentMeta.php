<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentMeta extends Model
{
    protected $table = 'content_meta';

    protected $fillable = [
        'site_id','content_id','meta_key','meta_value',
    ];
}
