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
    public static $ProfileWhiteList = ['sub', 'email','name','picture','givenName','familyName','locale'];


    public static function GetPayload($id_token){
        $result = [];
        try{
            $client = new \Google_Client(['client_id' => env("GOOGLE_PLUS_CLIENT_ID")]);
            $rawPayload = $client->verifyIdToken($id_token);
            foreach($rawPayload as $key => $value){
                $casedKey = camel_case($key);
                if(in_array($casedKey, static::$ProfileWhiteList)){
                    $result[$casedKey] = $value;
                }
            }
            return $result;
        }catch (\Exception $e){
            return false;
        }
    }

    public static function FindUserByPayload($payload){
        $uniqueId = static::GetUniqueIdByPayload($payload);
        $account = static::where("unique_id", "=", $uniqueId)->first();
        return is_null($account)
            ? null
            : $account->user;
    }

    public static function GetUniqueIdByPayload($payload){
        return $payload['sub'];
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
