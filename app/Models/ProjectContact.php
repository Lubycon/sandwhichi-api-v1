<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ProjectContact
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $project_id
 * @property int $contact_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectContact whereContactId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectContact whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectContact whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectContact whereProjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectContact whereUpdatedAt($value)
 */
class ProjectContact extends Model
{
    protected $guarded = array();
    protected $table = 'project_contacts';
    public $timestamps = true;
    protected $casts = [
        'id' => 'string',
        'project_id' => 'string',
        'contact_id' => 'string',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public static function ProjectHardSync(Project $project, Array $contacts){
        $project->contactList->each(function(ProjectContact $object, $index){
            if($object->contact){
                $object->contact->delete();
            }
            return $object->delete();
        });

        foreach($contacts as $contact){
            $model = Contact::create([
                'type_id' => $contact['typeId'],
                'information' => $contact['information'],
            ]);
            $project->contactList()->create(["contact_id" => $model->id]);
        }
        return true;
    }

    public function contact(){
        return $this->belongsTo(Contact::class,'contact_id','id');
    }
}