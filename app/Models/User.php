<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Log;
use Illuminate\Support\Str;
use Request;
use Abort;
use Carbon\Carbon;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * App\Models\User
 *
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $status
 * @property string $introduce
 * @property bool $email_accepted
 * @property bool $terms_of_service_accepted
 * @property bool $privacy_policy_accepted
 * @property \Carbon\Carbon $last_signin_time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \App\Models\SocialGoogleAccount $socialGoogleAccount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AccessToken[] $token
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User inDropTerm()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmailAccepted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereIntroduce($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereLastSigninTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePrivacyPolicyAccepted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereTermsOfServiceAccepted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword ,SoftDeletes;

    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'email_accepted' => 'boolean',
        'terms_of_service_accepted' => 'boolean',
        'privacy_policy_accepted' => 'boolean',
    ];
	protected $dates = ['created_at','updated_at','deleted_at','last_signin_time',];
    protected $hidden = ['password'];

    public static $dropUserMaintainDay = 30; // day

    public static function bindSigninData($request){
        return [
            "email" => $request->email,
            "password" => $request->password
        ];
    }
	public static function bindSignupData($request){
	    return [
	        "email" => $request->email,
            "password" => bcrypt($request->password),
            "name" => $request->name,
            "email_accepted" => $request->emailAccepted,
            "terms_of_service_accepted" => $request->termsOfServiceAccepted,
            "privacy_policy_accepted" => $request->privacyPolicyAccepted,
        ];
    }

    public static function CreateUser($request){
        $signupData = User::bindSignupData($request);
        return User::create($signupData);
    }

    public function getProfile(){
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'status' => $this->status,
            'introduce' => $this->introduce,
            'emailAccepted' => $this->email_accepted,
            'termsOfServiceAccepted' => $this->terms_of_service_accepted,
            'privacyPolicyAccepted' => $this->privacy_policy_accepted,
            'lastSigninTime' => $this->last_signin_time,
        ];
    }

    public function getTokens(){
        $access_token = JWTAuth::fromUser($this);
        $auth = JWTAuth::setToken($access_token)->authenticate();
        $refresh_token = RefreshToken::createToken($access_token);
        return [
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
        ];
    }

    public static function isMyId($user_id){
        if( Auth::check() ){
            return Auth::id() === $user_id;
        }
        return false;
    }
    public static function isActive(){
        if( Auth::check() ){
            return Auth::user()->status === 'active';
        }
        return false;
    }
    public static function isNotActive(){
        if( Auth::check() ){
            return Auth::user()->status !== 'active';
        }
        return false;
    }
    public static function isInactive(){
        if( Auth::check() ){
            return Auth::user()->status === 'inactive';
        }
        return false;
    }

    public static function isAvailableEmail($email){
        return static::withTrashed()
                ->where('email',$email)
                ->inDropTerm()
                ->exists() === false;
    }

    public static function isGhost(){
        return Auth::check() === false;
    }
    public static function isUser(){
        return Auth::check() === true;
    }
    public static function isAdmin(){
        // TODO Admin 테이블 별도 운영하기
//        if( Auth::check() ){
//            $userGrade = Auth::user()->grade;
//            return $userGrade === 'admin' || $userGrade === 'super_admin';
//        }
        return false;
    }

    public function scopeInDropTerm(Builder $query)
    {
        return $query
            ->where(function( Builder $query ) {
                $dropAddDay = static::$dropUserMaintainDay;
                $dropTermQuery = DB::raw('DATE_ADD(deleted_at, INTERVAL '.$dropAddDay.' DAY)');
                $now = Carbon::now()->toDateTimeString();
                $query
                    ->orWhere( $dropTermQuery , '>' , $now )
                    ->orWhere('deleted_at','=',null);
            });
    }

    public function token()
    {
        return $this->hasMany('App\Models\AccessToken','user_id','id');
    }

    public function getRememberToken()
    {
        return null; // not supported
    }

    public function setRememberToken($value)
    {
        // not supported
    }

    public function getRememberTokenName()
    {
        return null; // not supported
    }

    /**
     * Overrides the method to ignore the remember token.
     */
    public function setAttribute($key, $value)
    {
        $isRememberTokenAttribute = $key == $this->getRememberTokenName();
        if (!$isRememberTokenAttribute)
        {
            parent::setAttribute($key, $value);
        }
    }

    public function socialGoogleAccount(){
        return $this->hasOne('App\Models\SocialGoogleAccount', 'user_id', 'id');
    }
}
