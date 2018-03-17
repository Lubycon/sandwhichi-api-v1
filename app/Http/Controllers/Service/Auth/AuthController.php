<?php

namespace App\Http\Controllers\Service\Auth;

// Global
use App\Http\Requests\Service\Auth\AuthNaverSigninRequest;
use App\Http\Requests\Service\Auth\AuthNaverSignupRequest;
use App\Models\SocialGoogleAccount;
use App\Models\SocialNaverAccount;
use Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;
use Abort;

// Models
use App\Models\User;
use App\Models\RefreshToken;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;

// Requests
use App\Http\Requests\Service\Auth\AuthSigninRequest;
use App\Http\Requests\Service\Auth\AuthSignupRequest;
use App\Http\Requests\Service\Auth\AuthSigndropRequest;
use App\Http\Requests\Service\Auth\AuthSignoutRequest;
use App\Http\Requests\Service\Auth\AuthEmailExistRequest;
use App\Http\Requests\Service\Auth\AuthGoogleSignupRequest;
use App\Http\Requests\Service\Auth\AuthGoogleSigninRequest;

// Exceptions
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

// Jobs
use App\Jobs\Users\LastSigninTimeCheckerJob;

class AuthController extends Controller
{
    use ThrottlesLogins;

    public $user;
    public $uploader;

    public function __construct()
    {
        $this->user = Auth::user();
    }


    /**
     * @SWG\Post(
     *   path="/users/signin",
     *   summary="signin",
     *   operationId="signin",
     *   tags={"/Users/Auth"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign in into web site",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/signin")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function signin(AuthSigninRequest $request)
    {
        $credentials = User::bindSigninData($request);

        if (! $access_token = JWTAuth::attempt($credentials)) {
            return Abort::Error('0061');
        }

        $this->user = Auth::user();
        $this->dispatch(new LastSigninTimeCheckerJob($this->user));
        return response()->success($this->user->getTokens());
    }

    /**
     * @SWG\Post(
     *   path="/users/google/signin",
     *   summary="signin",
     *   operationId="signin_google",
     *   tags={"/Users/Auth"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign in into web site bia google plus",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/google/signin")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function googleSignin(AuthGoogleSigninRequest $request)
    {
        $socialPayload = SocialGoogleAccount::GetPayload($request->id_token);
        if (!$socialPayload)
            return Abort::Error('0063');

        $this->user = SocialGoogleAccount::FindUserByPayload($socialPayload);

        if($this->user !== null){
            return response()->success($this->user->getTokens());
        }

        return Abort::Error('0061');
    }

    /**
     * @SWG\Post(
     *   path="/users/naver/signin",
     *   summary="signin",
     *   operationId="signin_naver",
     *   tags={"/Users/Auth"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign in into web site bia naver plus",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/naver/signin")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function naverSignin(AuthNaverSigninRequest $request)
    {
        $profile = SocialNaverAccount::GetProfile($request->access_token);
        if(is_null($profile)){
            return Abort::Error('0063');
        }

        $uniqueId = $profile['id'];
        $this->user = SocialNaverAccount::FindUserByUniqueId($uniqueId);

        if($this->user !== null){
            return response()->success($this->user->getTokens());
        }

        return Abort::Error('0061');
    }

    /**
     * @SWG\Put(
     *   path="/users/signout",
     *   summary="signout",
     *   operationId="signout",
     *   tags={"/Users/Auth"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function signout(AuthSignoutRequest $request)
    {
        JWTAuth::invalidate();
        return response()->success();
    }

    /**
     * @SWG\Post(
     *   path="/users/signup",
     *   summary="signup",
     *   operationId="signup",
     *   tags={"/Users/Auth"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign up into web site",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/signup")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function signup(AuthSignupRequest $request)
    {
        if( $this->user = User::CreateUser($request) ){
            return response()->success($this->user->getTokens());
        }
        return Abort::Error('0040');
    }

    /**
     * @SWG\Post(
     *   path="/users/google/signup",
     *   summary="signup",
     *   operationId="signup_google",
     *   tags={"/Users/Auth"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign up into web site bia google plus",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/google/signup")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function googleSignup(AuthGoogleSignupRequest $request)
    {
        $socialPayload = SocialGoogleAccount::GetPayload($request->id_token);
        if (!$socialPayload){
            return Abort::Error('0063');
        }

        $checkExistUser = SocialGoogleAccount::FindUserByPayload($socialPayload);
        $uniqueId = SocialGoogleAccount::GetUniqueIdByPayload($socialPayload);

        if($checkExistUser !== null){
            // 이미 소셜 가입한경우.
            return Abort::Error('0016');
        }

        if( $this->user = User::CreateUser($request) ){
            SocialGoogleAccount::create([
                "user_id" => $this->user->id,
                "unique_id" => $uniqueId,
            ]);
        }

        return response()->success($this->user->getTokens());
    }

    /**
     * @SWG\Post(
     *   path="/users/naver/signup",
     *   summary="signup",
     *   operationId="signup_naver",
     *   tags={"/Users/Auth"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign up into web site bia naver",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/naver/signup")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function naverSignup(AuthNaverSignupRequest $request)
    {
        $profile = SocialNaverAccount::GetProfile($request->accessToken);
        if(is_null($profile)){
            return Abort::Error('0063');
        }

        $uniqueId = $profile['id'];
        $checkExistUser = SocialNaverAccount::FindUserByUniqueId($uniqueId);
        if($checkExistUser !== null){
            // 이미 소셜 가입한경우.
            return Abort::Error('0016');
        }

        if( $this->user = User::CreateUser($request) ){
            SocialNaverAccount::create([
                "user_id" => $this->user->id,
                "unique_id" => $uniqueId,
            ]);
        }
        return response()->success($this->user->getTokens());
    }

    /**
     * @SWG\Delete(
     *   path="/users/signdrop",
     *   summary="signdrop",
     *   operationId="signdrop",
     *   tags={"/Users/Auth"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function signdrop(AuthSigndropRequest $request)
    {
        // TODO Social token destroy
        if($this->user->delete()){
            if($googleAccount = $this->user->socialGoogleAccount){
                $googleAccount->delete();
            }

            JWTAuth::invalidate();
            return response()->success();
        }
        return Abort::Error('0040');
    }

    /**
     * @SWG\Post(
     *   path="/users/exists/email",
     *   summary="emailExist",
     *   operationId="emailExist",
     *   tags={"/Users/Auth"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Sign in into web site",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/auth/emailExist")
     *   ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function emailExist(AuthEmailExistRequest $request)
    {
        $result = User::isAvailableEmail($request->email) === false;
        return response()->success($result);
    }

    /**
     * @SWG\Get(
     *   path="/users/token/refresh",
     *   summary="Refresh access token",
     *   operationId="refreshAccessToken",
     *   tags={"/Users/Auth"},
     *     @SWG\Parameter(
     *      type="string",
     *      name="Authorization",
     *      in="header",
     *      default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDovL2FwaWxvY2FsLnBpeGVsc3RhaXJzLmNvbTo4MDgwL3YxL21lbWJlcnMvc2lnbmluIiwiaWF0IjoxNTA2MjQyNzU2LCJleHAiOjI0OTc3OTA1MTcwMTA5ODg3NTYsIm5iZiI6MTUwNjI0Mjc1NiwianRpIjoiNGFGVDV5VEtlaTdiVDVtWiJ9.AcYrVZBkvIepPi66IGUG0RMHDiv2b93kEEet3Ie0loU",
     *      required=true
     *     ),
     *     @SWG\Parameter(
     *      type="string",
     *      name="x-sandwhichi-refresh-token",
     *      in="header",
     *      default="dpMqeFNmM1x1g0akHDsGYyWcKM5kfpYnHxi2r9KsSSQThrD3E9l2",
     *      required=true
     *     ),
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function refreshAccessToken(Request $request)
    {
        $new_access_token = null;
        $refresh_token = null;

        try {
            if (!$this->user = JWTAuth::parseToken()->authenticate()){
                Abort::Error('0054');
            }
        } catch (TokenExpiredException $e) {
            // pass!
        } catch (TokenInvalidException $e) {
            Abort::Error('0061',$e);
        } catch (JWTException $e) {
            Abort::Error('0061',$e);
        }

        try {
            $expired_access_token = RefreshToken::getPureJWTToken();
            $payload = RefreshToken::getJWTTokenPayload($expired_access_token);
            $sub = $payload['sub'];
            $this->user = User::findOrFail($sub);
            $new_access_token = JWTAuth::refresh($expired_access_token);
            $refresh_token = RefreshToken::getValidToken();
        } catch (\Exception $e) {
            Abort::Error('0061',$e);
        }
        if( !is_null($refresh_token) ){
            $refresh_token->update([
                "jwt_token" => $new_access_token
            ]);
            return response()->success($new_access_token);
        }
        return Abort::Error('0062');
    }
}
