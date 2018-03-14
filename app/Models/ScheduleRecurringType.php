<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Schedule
 *
 * @mixin \Eloquent
 */
class ScheduleRecurringType extends Model
{
    protected $guarded = array();
    protected $table = 'schedule_recurring_types';
    public $timestamps = true;

}