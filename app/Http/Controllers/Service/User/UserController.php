<?php

namespace App\Http\Controllers\Service\User;

// Global
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
            "tokens" => $tokens,
            "profile" => $profile,
        ]);
    }
}
