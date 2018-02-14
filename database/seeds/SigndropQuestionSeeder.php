<?php

use Illuminate\Database\Seeder;
use App\Models\SigndropQuestion;

class SigndropQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('signdrop_questions')->truncate();
        $getJson = File::get("database/seeds/jsons/signdrop_questions.json");
        $json = json_decode($getJson,true);
        foreach( $json as $array ){
            SigndropQuestion::create($array);
        }
    }
}
