<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ProjectKeyword
 *
 * @property int $id
 * @property int $project_id
 * @property int $keyword_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectKeyword whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectKeyword whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectKeyword whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectKeyword whereKeywordId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectKeyword whereProjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectKeyword whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Keyword $keyword
 */
class ProjectKeyword extends Model
{
    protected $guarded = array();
    protected $table = 'project_keywords';
    public $timestamps = true;

    public function keyword(){
        return $this->belongsTo(Keyword::class);
    }
}