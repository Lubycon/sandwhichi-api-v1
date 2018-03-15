<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ContactType
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ContactType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ContactType whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ContactType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ContactType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ContactType whereUpdatedAt($value)
 */
class ContactType extends Model 
{
    protected $guarded = array();
    protected $table = 'contact_types';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}