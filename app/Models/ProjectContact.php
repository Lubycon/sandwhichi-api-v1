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
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectContacts whereContactId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectContacts whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectContacts whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectContacts whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectContacts whereProjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProjectContacts whereUpdatedAt($value)
 */
class ProjectContact extends Model
{
    protected $guarded = array();
    protected $table = 'project_contacts';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}