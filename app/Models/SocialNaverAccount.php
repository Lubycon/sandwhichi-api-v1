<?php

namespace App\Models;

use GuzzleHttp\Client;
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

    public static function GetTokens($code, $state){
        $result = null;
        try{
            // 엑세스 토큰 발급시 해당 코드는 다시 시용 불가능
            $client_id = env("NAVER_LOGIN_CLIENT_ID");
            $client_secret = env("NAVER_LOGIN_SECRET");
            $url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=$client_id&client_secret=$client_secret&code=$code&state=$state";
            $client = new Client();
            $response = $client->get($url);
            $response_code = $response->getStatusCode();
            if($response_code === 200) {
                //{
                //    "access_token": "AAAAN+ihOwEMFqH8vwlh/7vkIp/F0R0ISpnP37jdbAgLbZrv9JHv4I+6DaOlTXXJWNhxOVXIkvae2BIknZhooKOvyR4=",
                //    "refresh_token": "H5sFOO5fnN2ipp2fLMpccs1O5KOHzEYLuCWTKp6xisKx2dHsS0hZuwroipw0fvDjVwqH0Huqud9FFcB90PaxrA1iiNcnXeNgLm4NisqX85crTRkE3VvUjWt15U3A6EPUCYupO",
                //    "token_type": "bearer",
                //    "expires_in": "3600"
                //}
                $result = json_decode($response->getBody(),true);
            }
        }catch (\Exception $exception){
        }
        return $result;
    }

    public function refreshAccessToken(){
        $result = null;
        try{
            $client_id = env("NAVER_LOGIN_CLIENT_ID");
            $client_secret = env("NAVER_LOGIN_SECRET");
            $refresh_token = $this->refresh_token;
            $url = "https://nid.naver.com/oauth2.0/token?grant_type=refresh_token&client_id=$client_id&client_secret=$client_secret&refresh_token=$refresh_token";

            $client = new Client();
            $response = $client->get($url);
            $response_code = $response->getStatusCode();
            if($response_code === 200) {
                $result = json_decode($response->getBody(),true);
                if(isset($result['access_token'])){
                    $this->access_token = $result['access_token'];
                    $this->save();
                    $this->fresh();
                }
            }
        }catch (\Exception $exception){
        }
        return $result;
    }

    public function destroyAccessToken($code, $state){
        // TODO
        return true;
    }

    public static function GetProfile($access_token){
        $result = null;
        try{
            $client = new Client();
            $url = "https://openapi.naver.com/v1/nid/me";
            $response = $client->get($url, [
                'headers' => [
                    "Authorization" => "Bearer $access_token",
                ]
            ]);
            $response_code = $response->getStatusCode();
            if($response_code === 200) {
                //{
                //    "id": "41864220",
                //    "nickname": "ZEPOT",
                //    "profile_image": "https://ssl.pstatic.net/static/pwe/address/img_profile.png",
                //    "age": "20-29",
                //    "gender": "M",
                //    "email": "s_u_n_e@naver.com",
                //    "name": "김동규",
                //    "birthday": "10-27"
                //}
                $json_response = json_decode($response->getBody(),true);
                if($json_response['resultcode'] === "00"){
                    $result = $json_response['response'];
                }
            }
        }catch (\Exception $exception){
        }
        return $result;
    }

    public static function FindUserByUniqueId($uniqueId){
        $account = static::where("unique_id", "=", $uniqueId)->first();
        return is_null($account)
            ? null
            : $account->user;
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
