<?php

use Illuminate\Database\Seeder;
use App\Models\SigndropAnswer;

class SigndropAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('signdrop_answers')->truncate();
        $getJson = File::get("database/seeds/jsons/signdrop_answers.json");
        $json = json_decode($getJson,true);
        foreach( $json as $array ){
            SigndropAnswer::create($array);
        }
    }
}
