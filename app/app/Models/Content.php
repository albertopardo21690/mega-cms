<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'site_id','type','status','title','slug','content','excerpt',
        'author_id','parent_id','published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function meta()
    {
        return $this->hasMany(ContentMeta::class, 'content_id');
    }
}
