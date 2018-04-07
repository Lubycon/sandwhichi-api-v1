<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Keyword
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $count
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Keyword whereCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Keyword whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Keyword whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Keyword whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Keyword whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Keyword whereUpdatedAt($value)
 */
class Keyword extends Model
{
    protected $guarded = array();
    protected $table = 'keywords';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function countUp(){
        $this->fresh();
        $this->update([
            'count' => $this['count']+1
        ]);
    }

    public function countDown(){
        $this->fresh();
        $this->update([
            'count' => abs($this['count']-1)
        ]);
    }
}