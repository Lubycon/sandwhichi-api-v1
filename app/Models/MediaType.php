<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MediaType
 *
 * @mixin \Eloquent
 */
class MediaType extends Model 
{
    protected $guarded = array();
    protected $table = 'media_types';
    public $timestamps = true;

}