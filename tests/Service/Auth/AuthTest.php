<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

class AuthTest extends TestCase
{
    private $prefix = "/v1/users/";
    public $user;
    public $faker;
    public $token;
    public $headers;
    public $invalidHeaders;

    public function __setup(){
        $this->faker = Faker\Factory::create();
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
        $this->signupInvalidPassword();
        $this->signupInvalidNotAcceptTermsOfService();
        $this->signupInvalidNotAcceptPrivacyPolicy();

        // Email exists
        $this->emailExists();
        $this->emailExistsNoEmail();
        $this->emailExistsInvalid();

        // Signdrop
        $this->signdropWhenGhost();
        $this->signdropInvalid();
        $this->signdropSuccess();
    }

    public function signinSuccess(){
        $this->json('POST', $this->prefix."signin" , [
            'email' => $this->user->email,
            'password' => env('COMMON_PASSWORD'),
        ])
            ->assertResponseStatus(200);
        Auth::logout();
    }
    public function signinWhenUser(){
        $this->json('POST', $this->prefix."signin" , [
            'email' => $this->user->email,
            'password' => env('COMMON_PASSWORD'),
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
        $password = "password123!";
        $this->json('POST', $this->prefix."signup" , [
              "email" => $this->faker->unique()->email,
              "password" => $password,
              "name" => $this->faker->name,
              "emailAccepted" => true,
              "termsOfServiceAccepted" => true,
              "privacyPolicyAccepted" => true,
        ])
            ->assertResponseStatus(200);
        Auth::logout();
    }


    public function signupWhenUser(){
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($this->user);

        $this->json('POST', $this->prefix."signup" , [
            "email" => $this->faker->unique()->email,
            "password" => "password1234!",
            "emailAccepted" => true,
            "termsOfServiceAccepted" => true,
            "privacyPolicyAccepted" => true,
        ],[
            'Authorization' => 'Bearer ' . $token
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function signupInvalid(){
        $this->json('POST', $this->prefix."signup" , [
            "email" => $this->faker->unique()->email,
            "password" => "pass!",
            "emailAccepted" => true,
            "termsOfServiceAccepted" => true,
            "privacyPolicyAccepted" => true,
        ])
            ->assertResponseStatus(422);
        Auth::logout();
    }

    public function signupInvalidPassword(){
        $this->json('POST', $this->prefix."signup" , [
            "email" => $this->faker->unique()->email,
            "password" => "password",
            "emailAccepted" => true,
            "termsOfServiceAccepted" => true,
            "privacyPolicyAccepted" => true,
        ])
            ->assertResponseStatus(422);
        Auth::logout();
    }

    public function signupInvalidNotAcceptTermsOfService(){
        $this->json('POST', $this->prefix."signup" , [
            "email" => $this->faker->unique()->email,
            "password" => "pass!",
            "emailAccepted" => true,
            "termsOfServiceAccepted" => false,
            "privacyPolicyAccepted" => true,
        ])
            ->assertResponseStatus(422);
        Auth::logout();
    }

    public function signupInvalidNotAcceptPrivacyPolicy(){
        $this->json('POST', $this->prefix."signup" , [
            "email" => $this->faker->unique()->email,
            "password" => "pass!",
            "emailAccepted" => true,
            "termsOfServiceAccepted" => true,
            "privacyPolicyAccepted" => false,
        ])
            ->assertResponseStatus(422);
        Auth::logout();
    }

    public function signdropSuccess(){
        $user = factory(App\Models\User::class)->create();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($this->user);

        $this->json('DELETE', $this->prefix."signdrop" , [
        ],[
            'Authorization' => 'Bearer ' . $token
        ])
            ->assertResponseStatus(200);
        // Don't logout
    }

    public function signdropWhenGhost(){
        $this->json('DELETE', $this->prefix."signdrop" , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }

    public function signdropInvalid(){
        $this->json('DELETE', $this->prefix."signdrop" , [
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
            "email" => $this->faker->unique()->email,
        ])
            ->assertResponseStatus(200)
            ->seeJson([
                "result" => false,
            ]);
        Auth::logout();
    }


    public function emailExistsNoEmail(){
        $this->json('POST', $this->prefix."exists/email" , [
        ])
            ->assertResponseStatus(422);
        Auth::logout();
    }

    public function emailExistsInvalid(){
        $this->json('POST', $this->prefix."exists/email" , [
            "email" => "flkdsjflkassandwhichi.com"
        ])
            ->assertResponseStatus(422);
        Auth::logout();
    }
}
