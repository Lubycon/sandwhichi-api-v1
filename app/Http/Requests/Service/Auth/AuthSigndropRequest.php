<?php

namespace App\Http\Requests\Service\Auth;

use App\Http\Requests\Request;
use App\Models\User;

class AuthSigndropRequest extends Request
{
    public function authorize()
    {
        return User::isUser();
    }

    public function rules()
    {
        $requiredRule = [
//            'answerIds' => 'required|array'
        ];

        return $requiredRule;
    }
}
