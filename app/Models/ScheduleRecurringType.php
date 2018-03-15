<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Schedule
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ScheduleRecurringType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ScheduleRecurringType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ScheduleRecurringType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ScheduleRecurringType whereUpdatedAt($value)
 */
class ScheduleRecurringType extends Model
{
    protected $guarded = array();
    protected $table = 'schedule_recurring_types';
    public $timestamps = true;

}