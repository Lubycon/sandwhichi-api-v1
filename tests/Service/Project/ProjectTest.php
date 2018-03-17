<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

class ProjectTest extends TestCase
{
    private $prefix = "/v1/projects/";
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
        $this->createSuccess();
    }

    public function createSuccess(){
        // Full payload
        $this->json('POST', $this->prefix."" , [
            "title"=>"Project title",
            "description"=>"description",
            "profileImageUrl" => "https://youtube.com/123",
            "startedAt"=>"2017-10-10 12:00:00",
            "endsAt"=>"2018-11-11 12:00:00",
            "locationId"=>"1",
            "schedule"=> [
                "monday"=>true,
                "tuesday"=>true,
                "wednesday"=>true,
                "thursday"=>true,
                "saturday"=>true,
                "friday"=>true,
                "sunday"=>true,
                "isNegotiable"=>true,
                "scheduleRecurringId"=>"1",
                "startTime"=>"01:00:00",
                "endTime"=>"03:00:00"
            ],
            "contacts"=>[
                [
                "typeId"=>"1",
                "information"=>"daniel@good.com"
                ]
            ],
            "media"=>[
                [
                "typeId"=>"1",
                "url"=>"https://youtube.com/123"
                ]
            ]
        ],$this->headers)
        ->assertResponseStatus(200);

        // 미디어 없어도 성공
        $this->json('POST', $this->prefix."" , [
            "title"=>"Project title",
            "description"=>"description",
            "profileImageUrl" => "https://youtube.com/123",
            "startedAt"=>"2017-10-10 12:00:00",
            "endsAt"=>"2018-11-11 12:00:00",
            "locationId"=>"1",
            "schedule"=> [
                "monday"=>true,
                "tuesday"=>true,
                "wednesday"=>true,
                "thursday"=>true,
                "saturday"=>true,
                "friday"=>true,
                "sunday"=>true,
                "isNegotiable"=>true,
                "scheduleRecurringId"=>"1",
                "startTime"=>"01:00:00",
                "endTime"=>"03:00:00"
            ],
            "contacts"=>[
                [
                    "typeId"=>"1",
                    "information"=>"daniel@good.com"
                ]
            ],
        ],$this->headers)
            ->assertResponseStatus(200);
        Auth::logout();
    }

    public function createFailValidate(){
        // contact, 측 require 요소가 빠짐
        $this->json('POST', $this->prefix."" , [
            "title"=>"Project title",
            "description"=>"description",
            "profileImageUrl" => "https://youtube.com/123",
            "startedAt"=>"2017-10-10 12:00:00",
            "endsAt"=>"2018-11-11 12:00:00",
            "locationId"=>"1",
            "schedule"=> [
                "monday"=>true,
                "tuesday"=>true,
                "wednesday"=>true,
                "thursday"=>true,
                "saturday"=>true,
                "friday"=>true,
                "sunday"=>true,
                "isNegotiable"=>true,
                "scheduleRecurringId"=>"1",
                "startTime"=>"01:00:00",
                "endTime"=>"03:00:00"
            ]
        ],$this->headers)
            ->assertResponseStatus(422);

        // 여정 정보가 빠짐
        $this->json('POST', $this->prefix."" , [
            "title"=>"Project title",
            "description"=>"description",
            "profileImageUrl" => "https://youtube.com/123",
            "locationId"=>"1",
            "schedule"=> [
                "monday"=>true,
                "tuesday"=>true,
                "wednesday"=>true,
                "thursday"=>true,
                "saturday"=>true,
                "friday"=>true,
                "sunday"=>true,
                "isNegotiable"=>true,
                "scheduleRecurringId"=>"1",
                "startTime"=>"01:00:00",
                "endTime"=>"03:00:00"
            ],
            "contacts"=>[
                [
                    "typeId"=>"1",
                    "information"=>"daniel@good.com"
                ]
            ],
        ],$this->headers)
            ->assertResponseStatus(422);
    }

    public function createFailWhenGhost(){
        $this->json('POST', $this->prefix."" , [
            "title"=>"Project title",
            "description"=>"description",
            "profileImageUrl" => "https://youtube.com/123",
            "locationId"=>"1",
            "schedule"=> [
                "monday"=>true,
                "tuesday"=>true,
                "wednesday"=>true,
                "thursday"=>true,
                "saturday"=>true,
                "friday"=>true,
                "sunday"=>true,
                "isNegotiable"=>true,
                "scheduleRecurringId"=>"1",
                "startTime"=>"01:00:00",
                "endTime"=>"03:00:00"
            ],
            "contacts"=>[
                [
                    "typeId"=>"1",
                    "information"=>"daniel@good.com"
                ]
            ],
        ])
            ->assertResponseStatus(403);
    }

    public function createFailWhenInactive(){
        // TODO 회원 인증 구현시 작업
    }
}
