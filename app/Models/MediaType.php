<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MediaType
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MediaType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MediaType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MediaType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\MediaType whereUpdatedAt($value)
 */
class MediaType extends Model 
{
    protected $guarded = array();
    protected $table = 'media_types';
    public $timestamps = true;

}