<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

class AuthTest extends TestCase
{
    private $prefix = "/v1/members/";
    public $user;
    public $token;
    public $headers;
    public $invalidHeaders;

    public function __setup(){
        $this->user = factory(App\Models\User::class)->create();
        $this->token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($this->user);
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->token
        ];
        $this->invalidHeaders = [
            'Authorization' => 'Bearer InvalidToken'
        ];
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->__setup();

        // Signin
        $this->signinSuccess();
        $this->signinWhenUser();
        $this->signinInvalid();

        // Signout
        $this->signoutSuccess();
        $this->signoutWhenGhost();
        $this->signoutInvalid();
        $this->signoutWithoutToken();

        // Signup
        $this->signupSuccess();
        $this->signupWhenUser();
        $this->signupInvalid();

        // Email exists
        $this->emailExists();
        $this->emailExistsInvalid();

        // Nickname exists
        $this->nicknameExists();
        $this->nicknameExistsInvalid();

        // Signdrop
        $this->signdropSurveyList();
        $this->signdropWhenGhost();
        $this->signdropInvalid();
        $this->signdropSuccess();
    }

    public function signinSuccess(){
        $this->json('POST', $this->prefix."signin" , [
            'email' => 'test@sandwhichi.com',
            'password' => '1234qwer',
        ])
            ->assertResponseStatus(200);
        Auth::logout();
    }
    public function signinWhenUser(){
        $this->json('POST', $this->prefix."signin" , [
            'email' => 'test@sandwhichi.com',
            'password' => '1234qwer',
        ],$this->headers)
            ->assertResponseStatus(403);
        Auth::logout();
    }
    public function signinInvalid(){
        $this->json('POST', $this->prefix."signin" , [
            'email' => 'test@sandwhichi.com',
            'password' => 'helloworld',
        ])
            ->assertResponseStatus(401);
        Auth::logout();
    }


    public function signoutSuccess(){
        $this->json('PUT', $this->prefix."signout" , [
        ],$this->headers)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function signoutWhenGhost(){
        $this->json('PUT', $this->prefix."signout" , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function signoutInvalid(){
        $this->json('PUT', $this->prefix."signout" , [
        ],$this->invalidHeaders)
            ->assertResponseStatus(401);
        Auth::logout();
    }


    public function signoutWithoutToken(){
        $this->json('PUT', $this->prefix."signout" , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }


    public function signupSuccess(){
        $randStr = Str::random(20);
        $this->json('POST', $this->prefix."signup" , [
              "email" => $randStr."@sandwhichi.com",
              "password" => $randStr,
              "nickname" => $randStr,
              "newsletterAccepted" => true,
              "termsOfServiceAccepted" => true
        ])
            ->assertResponseStatus(200);
        Auth::logout();
    }


    public function signupWhenUser(){
        $this->json('POST', $this->prefix."signup" , [
            "email" => "nononoenofnd@sandwhichi.com",
            "password" => "password1234!",
            "nickname" => "usernicks",
            "newsletterAccepted" => true,
            "termsOfServiceAccepted" => true
        ],$this->headers)
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function signupInvalid(){
        $this->json('POST', $this->prefix."signup" , [
            "email" => "nonosandwhichi.com",
            "password" => "pass!",
            "nickname" => "admin",
            "newsletterAccepted" => false,
            "termsOfServiceAccepted" => false,
        ])
            ->assertResponseStatus(422);
        Auth::logout();
    }

    public function signdropSurveyList(){
        $this->json('GET', $this->prefix."signdrop/survey/list" , [
        ],$this->headers)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function signdropSuccess(){
        $this->json('DELETE', $this->prefix."signdrop" , [
            "answerIds" => [1,7]
        ],$this->headers)
            ->assertResponseStatus(200);
        // Don't logout
    }

    public function signdropWhenGhost(){
        $this->json('DELETE', $this->prefix."signdrop" , [
            "answerIds" => [1,7]
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function signdropInvalid(){
        $this->json('DELETE', $this->prefix."signdrop" , [
        ],$this->headers)
            ->assertResponseStatus(422);
        Auth::logout();

        $this->json('DELETE', $this->prefix."signdrop" , [
            "answerIds" => 100
        ],$this->headers)
            ->assertResponseStatus(422);
        Auth::logout();

        $this->json('DELETE', $this->prefix."signdrop" , [
            "answerIds" => [1,7]
        ],$this->invalidHeaders)
            ->assertResponseStatus(401);
        Auth::logout();
    }

    public function emailExists(){
        $this->json('POST', $this->prefix."exists/email" , [
            "email" => "test@sandwhichi.com"
        ])
            ->assertResponseStatus(200)
            ->seeJson([
                "result" => true,
            ]);

        $this->json('POST', $this->prefix."exists/email" , [
            "email" => "flkdsjflkas@sandwhichi.com"
        ])
            ->assertResponseStatus(200)
            ->seeJson([
                "result" => false,
            ]);
        Auth::logout();
    }


    public function emailExistsInvalid(){
        $this->json('POST', $this->prefix."exists/email" , [
        ])
            ->assertResponseStatus(422);
        Auth::logout();
    }


    public function nicknameExists(){
        $this->json('POST', $this->prefix."exists/nickname" , [
            "nickname" => "Admin"
        ])
            ->assertResponseStatus(200)
            ->seeJson([
                "result" => true,
            ]);

        $this->json('POST', $this->prefix."exists/nickname" , [
            "nickname" => "AdminAdminAdmin"
        ])
            ->assertResponseStatus(200)
            ->seeJson([
                "result" => false,
            ]);
        Auth::logout();
    }

    public function nicknameExistsInvalid(){
        $this->json('POST', $this->prefix."exists/nickname" , [
        ])
            ->assertResponseStatus(422);
        Auth::logout();
    }
}
