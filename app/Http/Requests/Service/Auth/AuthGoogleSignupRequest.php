<?php

namespace App\Http\Requests\Service\Auth;

use App\Http\Requests\Request;
use App\Models\User;

use App\Traits\AuthorizesRequestsOverLoad;
use Log;

class AuthGoogleSignupRequest extends Request
{
    use AuthorizesRequestsOverLoad;

    public function authorize()
    {
        return User::isGhost();
    }

    /**
     *  @SWG\Definition(
     *   definition="auth/google/signup",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"id_token", "email","password","name","newsletterAccepted","termsOfServiceAccepted"},
     *           @SWG\Property(property="id_token", type="string", default=""),
     *           @SWG\Property(property="email", type="string", default="test@sandwhichi.com"),
     *           @SWG\Property(property="password", type="string", default="password123!"),
     *           @SWG\Property(property="name", type="string", default="홍길동"),
     *           @SWG\Property(property="emailAccepted", type="boolean", default=true),
     *           @SWG\Property(property="termsOfServiceAccepted", type="boolean", default=true),
     *           @SWG\Property(property="privacyPolicyAccepted", type="boolean", default=true),
     *       )
     *   }
     * )
     */
    public function rules()
    {
        $requiredRule = [
            "id_token" => "required|min:10",
            "email" => "required|availableEmail|email|max:255",
            "password" => "required|string|availablePassword|max:1000",
            "name" => "required|max:20",
            "emailAccepted" => "required|boolean",
            "termsOfServiceAccepted" => "required|accepted",
            "privacyPolicyAccepted" => "required|accepted",
        ];
        return $requiredRule;
    }
}

