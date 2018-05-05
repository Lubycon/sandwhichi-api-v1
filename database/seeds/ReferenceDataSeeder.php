<?php

use Illuminate\Database\Seeder;

class ReferenceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\ProjectDescriptionQuestions::create([
            "question" => "어떤 주제를 다루는 프로젝트 인가요?"
        ]);
        \App\Models\ProjectDescriptionQuestions::create([
            "question" => "어떤 사람들이 필요한지 궁금해요"
        ]);
        \App\Models\ProjectDescriptionQuestions::create([
            "question" => "이 프로젝트를 한마디로 표현 한다면?"
        ]);
        \App\Models\ProjectDescriptionQuestions::create([
            "question" => "자유롭게 설명해 주세요"
        ]);

        \App\Models\MediaType::create([
            "name" => "이미지"
        ]);
        \App\Models\MediaType::create([
            "name" => "유튜브"
        ]);


        \App\Models\ContactType::create([
            "name" => "이메일"
        ]);
        \App\Models\ContactType::create([
            "name" => "카카오 오픈채팅"
        ]);
        \App\Models\ContactType::create([
            "name" => "카카오톡"
        ]);
        \App\Models\ContactType::create([
            "name" => "페이스북"
        ]);

        \App\Models\ScheduleRecurringType::create([
            "name" => "매일"
        ]);
        \App\Models\ScheduleRecurringType::create([
            "name" => "매주"
        ]);
        \App\Models\ScheduleRecurringType::create([
            "name" => "격주"
        ]);
        \App\Models\ScheduleRecurringType::create([
            "name" => "3주 마다"
        ]);
        \App\Models\ScheduleRecurringType::create([
            "name" => "매달 첫주"
        ]);
        \App\Models\ScheduleRecurringType::create([
            "name" => "매달 두째 주"
        ]);
        \App\Models\ScheduleRecurringType::create([
            "name" => "매달 셋째 주"
        ]);
        \App\Models\ScheduleRecurringType::create([
            "name" => "매달 마지막 주"
        ]);
    }
}
