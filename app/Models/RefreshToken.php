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
 * App\Models\RefreshToken
 *
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken expired()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken latest()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken my()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken notExpired()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken matchJWT()
 * @mixin \Eloquent
 * @property string $id
 * @property string $user_id
 * @property string $token
 * @property string $jwt_token
 * @property string $expired_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken whereExpiredAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken whereJwtToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RefreshToken whereUserId($value)
 */
class RefreshToken extends Model
{
    use SoftDeletes;

    public static $refreshTokenHeaderKey = "x-sandwhichi-refresh-token";
    public static $randomLength = 50;
    public static $expiredHours = 168; // 7days
    protected $guarded = array();
    protected $hidden = ['token'];
    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
    ];

    public static function getValidToken(){
        $refresh_token = app('request')->headers->get(static::$refreshTokenHeaderKey);
        return static::my()->notExpired()->matchJWT()->where('token', $refresh_token)->first();
    }

    public static function createToken($jwt_token){
        $token = static::create([
            "user_id" => Auth::user()->id,
            "token" => static::generateToken(),
            "jwt_token" => $jwt_token,
            "expired_at" => Carbon::now()->addHours(static::$expiredHours),
        ]);
        return $token['token'];
    }

    public static function generateToken(){
        $device = app('request')->clientInfo['device']['typeCode'];
        $userId = Auth::user()->id;
        $deviceCode = $device;
        $randomStr = Str::random(static::$randomLength);
        return $deviceCode.$randomStr.$userId;
    }

    public static function destroyCurrentToken(){
        if(Auth::check()){
            return static::my()->matchJWT()->delete();
        }
    }

    public static function destroyExpiredTokens(){
        static::expired()->delete();
    }

    public static function getPureJWTToken($header = 'authorization', $method = 'bearer')
    {
        $header = app('request')->headers->get($header);
        if (! starts_with(strtolower($header), $method)) {
            return false;
        }
        return trim(str_ireplace($method, '', $header));
    }

    public static function getJWTTokenPayload($jwt_token){
        $explode_token = explode('.',$jwt_token);
        return json_decode(base64_decode($explode_token[1]),true);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMy($query)
    {
        return $query->where('user_id', Auth::user()->id);
    }
    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMatchJWT($query)
    {
        $token = static::getPureJWTToken();
        return $query->where('jwt_token', $token);
    }
    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', "desc");
    }
    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotExpired($query)
    {
        return $query->where('expired_at', ">", Carbon::now()->toDateTimeString());
    }
    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where('expired_at', "<", Carbon::now()->toDateTimeString());
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
