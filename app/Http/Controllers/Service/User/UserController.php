<?php

namespace App\Http\Controllers\Service\User;

// Global
use App\Http\Requests\Service\User\UserProfileRequest;
use App\Models\SocialGoogleAccount;
use App\Models\SocialNaverAccount;
use Log;
use Auth;
use Abort;

// Models
use App\Models\User;

// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Requests

class UserController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }


    /**
     * @SWG\Get(
     *   path="/users/me",
     *   summary="profile",
     *   operationId="prfile",
     *   tags={"/Users"},
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
    public function profile(UserProfileRequest $request){
        return response()->success($this->user->getProfile());
    }

    /**
     * @SWG\Get(
     *   path="/users/google/profile/id-token/{id_token}",
     *   @SWG\Parameter(
     *     name="id_token",
     *     description="id token from google plus",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="Write token",
     *   ),
     *   summary="profile",
     *   operationId="google_prfile",
     *   tags={"/Users"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function googleProfile(Request $request, $id_token){
        $socialPayload = SocialGoogleAccount::GetPayload($id_token);
        if (!$socialPayload){
            return Abort::Error('0063');
        }
        return response()->success($socialPayload);
    }

    /**
     * @SWG\Get(
     *   path="/users/naver/profile/code/{code}/state/{state}",
     *   @SWG\Parameter(
     *     name="code",
     *     description="naver login code",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="Write code",
     *   ),
     *   @SWG\Parameter(
     *     name="state",
     *     description="naver login state",
     *     in="path",
     *     required=true,
     *     type="string",
     *     default="Write state",
     *   ),
     *   summary="profile",
     *   operationId="naver_prfile",
     *   tags={"/Users"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    public function naverProfile(Request $request, $code, $state){
        $tokens = SocialNaverAccount::GetTokens($request->code, $request->state);
        if (is_null($tokens)){
            return Abort::Error('0063');
        }
        $accessToken = $tokens['access_token'];

        $profile = SocialNaverAccount::GetProfile($accessToken);
        if(is_null($profile)){
            return Abort::Error('0063');
        }
        return response()->success([
            "accessToken" => $accessToken,
            "profile" => $profile,
        ]);
    }
}
