<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Schedule
 *
 * @mixin \Eloquent
 * @property int $id
 * @property bool $monday
 * @property bool $tuesday
 * @property bool $wednesday
 * @property bool $thursday
 * @property bool $friday
 * @property bool $saturday
 * @property bool $sunday
 * @property int $schedule_recurring_id
 * @property bool $is_negotiable
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereFriday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereIsNegotiable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereMonday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereSaturday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereScheduleRecurringId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereSunday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereThursday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereTuesday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereWednesday($value)
 * @property string $started_at
 * @property string $ends_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereEndsAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereStartedAt($value)
 * @property string $start_time
 * @property string $end_time
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereEndTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereStartTime($value)
 */
class Schedule extends Model 
{
    protected $guarded = array();
    protected $table = 'schedules';
    public $timestamps = true;

}