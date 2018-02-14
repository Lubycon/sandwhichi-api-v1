<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Log;
use Abort;

/**
 * App\Models\PasswordReset
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PasswordReset whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PasswordReset whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PasswordReset whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PasswordReset whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PasswordReset extends Model {

    public static $rules = [
        [
            "score" => 1,
            "regex" => "/[a-z]/",
            "name" => "Lower case",
        ],
        [
            "score" => 1,
            "regex" => "/[A-Z]/",
            "name" => "Upper case",
        ],
        [
            "score" => 1,
            "regex" => "/[0-9]/",
            "name" => "Number",
        ],
        [
            "score" => 2,
            "regex" => "/[\?\!\@\#\$\%\^\*\+\-\_\.\,\`]/",
            "name" => "Special",
        ],
        [
            "score" => 5,
            "regex" => "/^(?:(.)(?!\1\1))*$/",
            "name" => "Repeating",
        ],
        [
            "score" => 10,
            "regex" => "/^.{8,}$/",
            "name" => "Length",
        ],
    ];
    protected $guarded = array();


    protected static $expireTime = 30; //minutes

    public static function getByToken($token){
        $passwordReset = PasswordReset::wheretoken($token)->firstOrFail();
        return $passwordReset;
    }
    public static function getByEmail($token){
        $passwordReset = PasswordReset::whereemail($token)->firstOrFail();
        return $passwordReset;
    }
    public static function getUserByToken($token){
        return PasswordReset::getByToken($token)->user;
    }
    public function expiredCheck(){
        if( $this->getDiffTime() ){
            return true;
        }else{
            Abort::Error('0040','Over Expired time');
        }
    }
    public function getDiffTime(){
        $nowTime = Carbon::now();
        $expiredTime = $this->created_at->addMinutes($this::$expireTime);
        if($nowTime > $expiredTime) return 0;
        return $nowTime->diffInSeconds($expiredTime);
    }

    public static function isAvailable($password) {
        $score = 0;
        $max = 0;

        if($password && strlen($password)> 0) {
            // 조건 정규식 배열에 forEach

            foreach( static::$rules as $rule ){
                $max += $rule['score']; // 나중에 수행할 정규화를 위해 모든 조건의 점수를 더한다
                if(preg_match($rule['regex'], $password)) {
                    $score += $rule['score']; // 테스트를 통과했으면 점수를 더한다
                }
                else {
                    $score -= $rule['score']; // 테스트를 통과하지 못했으면 점수를 뺀다
                }
            }
        }
        else $score = 0;

        // 이렇게 나온 점수가 양수일 경우에만 0~100 사이의 정수로 정규화한다
        $score = $score > 0 ? ($score / $max) * 100 : 0;
        return $score >= 70;
    }


	public function user()
	{
		return $this->belongsTo('App\Models\User', 'email', 'email');
	}

}