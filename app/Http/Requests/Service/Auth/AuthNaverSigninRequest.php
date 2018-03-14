<?php

namespace App\Http\Requests\Service\Auth;

use App\Http\Requests\Request;
use App\Models\User;

use App\Traits\AuthorizesRequestsOverLoad;
use Log;

class AuthNaverSigninRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isGhost();
    }

    /**
     *  @SWG\Definition(
     *   definition="auth/naver/signin",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"access_token"},
     *           @SWG\Property(property="access_token", type="string", default="naver access token"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "access_token" => "required",
        ];
        return $requiredRule;
    }
}

