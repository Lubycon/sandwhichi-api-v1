<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Project
 *
 * @mixin \Eloquent
 */
class Project extends Model 
{
    protected $guarded = array();
    protected $table = 'projects';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}