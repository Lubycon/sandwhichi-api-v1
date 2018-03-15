<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProjectView
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property string $user_ip
 * @property int $project_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectView whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectView whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectView whereProjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectView whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectView whereUserIp($value)
 */
class ProjectView extends Model 
{
    protected $guarded = array();
    protected $table = 'project_views';
    public $timestamps = true;

}