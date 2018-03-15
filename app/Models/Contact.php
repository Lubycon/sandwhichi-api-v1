<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Contact
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $type_id
 * @property string $information
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Contact whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Contact whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Contact whereInformation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Contact whereTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Contact whereUpdatedAt($value)
 */
class Contact extends Model 
{
    protected $guarded = array();
    protected $table = 'contacts';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}