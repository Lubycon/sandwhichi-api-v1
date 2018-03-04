<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use Abort;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * App\Models\SocialGoogleAccount
 *
 * @property-read \App\Models\User $user
 * @mixin \Eloquent
 * @property string $id
 * @property string $user_id
 * @property string $id_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialGoogleAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialGoogleAccount whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialGoogleAccount whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialGoogleAccount whereIdToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialGoogleAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialGoogleAccount whereUserId($value)
 */
class SocialGoogleAccount extends Model
{
    use SoftDeletes;

    protected $guarded = array();
    protected $hidden = ['id_token'];
    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
    ];

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
