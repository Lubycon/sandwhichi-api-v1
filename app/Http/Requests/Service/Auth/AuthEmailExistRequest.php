<?php

namespace App\Http\Requests\Service\Auth;

use App\Http\Requests\Request;
use App\Models\User;
use App\Traits\AuthorizesRequestsOverLoad;

use Log;

class AuthEmailExistRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return true;
    }

    /**
     *  @SWG\Definition(
     *   definition="auth/emailExist",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"email"},
     *           @SWG\Property(property="email", type="string", default="test@sandwhichi.com"),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "email" => "required|email",
        ];
        return $requiredRule;
    }
}
