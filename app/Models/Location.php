<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Location
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $address_0
 * @property string $address_1
 * @property string $address_2
 * @property float $latitude
 * @property float $longitude
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Location whereAddress0($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Location whereAddress1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Location whereAddress2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Location whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Location whereLatitude($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Location whereLongitude($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Location whereUpdatedAt($value)
 */
class Location extends Model 
{
    protected $guarded = array();
    protected $table = 'locations';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}