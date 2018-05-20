<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Project
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $profile_image_url
 * @property int $location_id
 * @property int $schedule_id
 * @property string $started_at
 * @property string $ends_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project whereEndsAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project whereLocationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project whereProfileImageUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project whereScheduleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project whereStartedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProjectContact[] $contactList
 * @property-read \App\Models\Location $location
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProjectMedia[] $mediaList
 * @property-read \App\Models\Schedule $schedule
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProjectAbility[] $abilityList
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProjectKeyword[] $keywordList
 */
class Project extends Model
{
    protected $guarded = array();
    protected $table = 'projects';
    public $timestamps = true;
    protected $casts = [
        'id' => 'string',
        'location_id' => 'string',
        'schedule_id' => 'string',
    ];

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function location(){
        return $this->hasOne(Location::class);
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }

    public function contactList(){
        return $this->hasMany(ProjectContact::class);
    }

    public function mediaList(){
        return $this->hasMany(ProjectMedia::class);
    }

    public function keywordList(){
        return $this->hasMany(ProjectKeyword::class);
    }

    public function abilityList(){
        return $this->hasMany(ProjectAbility::class);
    }
}