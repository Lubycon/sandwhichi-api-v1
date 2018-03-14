<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ProjectMedia
 *
 * @mixin \Eloquent
 */
class ProjectMedia extends Model 
{
    protected $guarded = array();
    protected $table = 'project_media';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}