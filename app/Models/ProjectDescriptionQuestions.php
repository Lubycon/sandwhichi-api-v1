<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProjectDescriptionQuestions
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $question
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectDescriptionQuestions whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectDescriptionQuestions whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectDescriptionQuestions whereQuestion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectDescriptionQuestions whereUpdatedAt($value)
 */
class ProjectDescriptionQuestions extends Model 
{
    protected $guarded = array();
    protected $table = 'project_description_questions';
    public $timestamps = true;

}