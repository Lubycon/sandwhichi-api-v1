<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectKeywordAbilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keywords', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30)->index();
            $table->integer('count')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('abilities', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30)->index();
            $table->integer('count')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_abilities', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->integer('ability_id')->unsigned();
            $table->timestamps();
            $table->index([
                'project_id',
                'ability_id'
            ]);
        });

        Schema::create('project_keywords', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->integer('keyword_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->index([
                'project_id',
                'keyword_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('keywords');
        Schema::drop('abilities');
        Schema::drop('project_abilities');
        Schema::drop('project_keywords');
    }
}
