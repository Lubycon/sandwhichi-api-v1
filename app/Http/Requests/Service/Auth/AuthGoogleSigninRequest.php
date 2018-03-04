<?php

namespace App\Http\Requests\Service\Auth;

use App\Http\Requests\Request;
use App\Models\User;

use App\Traits\AuthorizesRequestsOverLoad;
use Log;

class AuthGoogleSigninRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isGhost();
    }

    /**
     *  @SWG\Definition(
     *   definition="auth/signin/google",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"id_token"},
     *           @SWG\Property(property="id_token", type="string", default=""),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "id_token" => "required|min:10",
        ];
        return $requiredRule;
    }
}

