<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ProjectAbility
 *
 * @property int $id
 * @property int $project_id
 * @property int $ability_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectAbility whereAbilityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectAbility whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectAbility whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectAbility whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectAbility whereProjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectAbility whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProjectAbility extends Model
{
    protected $guarded = array();
    protected $table = 'project_abilities';
    public $timestamps = true;

}