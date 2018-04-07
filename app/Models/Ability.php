<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Ability
 *
 * @property int $id
 * @property string $name
 * @property int $count
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ability whereCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ability whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ability whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ability whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ability whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ability whereDeletedAt($value)
 */
class Ability extends Model
{
    protected $guarded = array();
    protected $table = 'abilities';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
}