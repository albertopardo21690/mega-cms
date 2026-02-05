<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
    protected $fillable = ['site_id','taxonomy_key','label','settings'];

    protected $casts = ['settings' => 'array'];

    public function terms()
    {
        return $this->hasMany(Term::class, 'taxonomy_id');
    }
}
