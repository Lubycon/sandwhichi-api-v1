<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ProjectMedia
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $project_id
 * @property int $media_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectMedia whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectMedia whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectMedia whereMediaId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectMedia whereProjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectMedia whereUpdatedAt($value)
 */
class ProjectMedia extends Model 
{
    protected $guarded = array();
    protected $table = 'project_media';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}