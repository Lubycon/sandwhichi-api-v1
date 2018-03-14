<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Schedule
 *
 * @mixin \Eloquent
 */
class Schedule extends Model 
{
    protected $guarded = array();
    protected $table = 'schedules';
    public $timestamps = true;

}