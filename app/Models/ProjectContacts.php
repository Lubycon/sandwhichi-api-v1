<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ProjectContacts
 *
 * @mixin \Eloquent
 */
class ProjectContacts extends Model 
{
    protected $guarded = array();
    protected $table = 'project_contacts';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}