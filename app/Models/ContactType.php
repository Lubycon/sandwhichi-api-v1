<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ContactType
 *
 * @mixin \Eloquent
 */
class ContactType extends Model 
{
    protected $guarded = array();
    protected $table = 'contact_types';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}