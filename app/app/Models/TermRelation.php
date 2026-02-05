<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermRelation extends Model
{
    protected $fillable = ['site_id','content_id','term_id'];
}
