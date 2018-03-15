<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Media
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $type_id
 * @property string $url
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Media whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Media whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Media whereTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Media whereUrl($value)
 */
class Media extends Model 
{
    protected $guarded = array();
    protected $table = 'media';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}