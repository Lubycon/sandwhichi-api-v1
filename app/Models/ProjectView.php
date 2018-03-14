<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProjectView
 *
 * @mixin \Eloquent
 */
class ProjectView extends Model 
{
    protected $guarded = array();
    protected $table = 'project_views';
    public $timestamps = true;

}