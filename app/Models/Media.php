<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Media
 *
 * @mixin \Eloquent
 */
class Media extends Model 
{
    protected $guarded = array();
    protected $table = 'media';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}