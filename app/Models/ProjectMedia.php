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
    protected $casts = [
        'id' => 'string',
        'project_id' => 'string',
        'media_id' => 'string',
    ];

    use SoftDeletes;

    protected $dates = ['deleted_at'];


    public static function ProjectHardSync(Project $project, Array $media){
        $project->mediaList->each(function(ProjectContact $object, $index){
            $object->media->delete();
            return $object->delete();
        });

        foreach($media as $content){
            $model = Media::create([
                'type_id' => $content['typeId'],
                'url' => Media::S3RawImageParse($content['url']),
            ]);
            $project->contactList()->create(["contact_id" => $model->id]);
        }
        return true;
    }

    public function media(){
        return $this->belongsTo(Media::class,'media_id','id');
    }
}