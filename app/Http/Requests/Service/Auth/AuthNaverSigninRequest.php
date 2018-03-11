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
     *           required={"state","code"},
     *           @SWG\Property(property="code", type="string", default=""),
     *           @SWG\Property(property="state", type="string", default=""),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "state" => "required",
            "code" => "required",
        ];
        return $requiredRule;
    }
}

