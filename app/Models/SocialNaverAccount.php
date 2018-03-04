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
 * App\Models\SocialNaverAccount
 *
 * @property-read \App\Models\User $user
 * @mixin \Eloquent
 * @property string $id
 * @property string $user_id
 * @property string $unique_id
 * @property string $access_token
 * @property string $refresh_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialNaverAccount whereAccessToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialNaverAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialNaverAccount whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialNaverAccount whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialNaverAccount whereRefreshToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialNaverAccount whereUniqueId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialNaverAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialNaverAccount whereUserId($value)
 */
class SocialNaverAccount extends Model
{
    use SoftDeletes;

    protected $guarded = array();
    protected $hidden = ['refresh_token'];
    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
    ];

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
