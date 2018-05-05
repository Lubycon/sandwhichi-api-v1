<?php

use Illuminate\Database\Seeder;

class LocationDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->truncate();
        $getJson = File::get("database/seeds/jsons/locations.json");
        $json = json_decode($getJson,true);
        foreach( $json as $array ){
            \App\Models\Location::create($array);
        }
    }
}
