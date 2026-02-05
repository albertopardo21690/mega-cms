<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // ✅ IMPORTANTE

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'site_id','disk','path','filename','mime','size','author_id'
    ];

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
