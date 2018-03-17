<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

class User extends TestCase
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

        $this->profileSuccess();
        $this->profileWhenGhost();
    }

    public function profileSuccess(){
        $this->json('GET', $this->prefix."me" , [
        ], $this->headers)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function profileWhenGhost(){
        $this->json('GET', $this->prefix."me" , [
        ])
            ->assertResponseStatus(403);
        Auth::logout();
    }
}
