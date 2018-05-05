<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectAndRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // project
        Schema::create('projects', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->text('description');
            $table->text('profile_image_url');
            $table->integer('location_id')->unsigned();
            $table->integer('schedule_id')->unsigned();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_contacts', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->integer('contact_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_media', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->integer('media_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        // view
        Schema::create('project_views', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('user_ip');
            $table->integer('project_id')->unsigned()->index();
            $table->timestamps();
        });

        Schema::create('project_description_questions', function(Blueprint $table) {
            $table->increments('id');
            $table->string('question');
            $table->timestamps();
        });

        // user contact
        Schema::create('contacts', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned()->index();
            $table->string('information', 100);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('contact_types', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // location
        Schema::create('locations', function(Blueprint $table) {
            $table->increments('id');
            $table->string('address_0', 20);
            $table->string('address_1', 20)->nullable();
            $table->string('address_2', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // media
        Schema::create('media', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned()->index();
            $table->text('url');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('media_types', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name',20);
            $table->timestamps();
        });

        // schedule
        Schema::create('schedules', function(Blueprint $table) {
            $table->increments('id');
            $table->boolean('monday')->default(false);
            $table->boolean('tuesday')->default(false);
            $table->boolean('wednesday')->default(false);
            $table->boolean('thursday')->default(false);
            $table->boolean('friday')->default(false);
            $table->boolean('saturday')->default(false);
            $table->boolean('sunday')->default(false);
            $table->integer('schedule_recurring_id')->unsigned()->nullable();
            $table->boolean('is_negotiable')->unsigned()->default(false);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamps();
        });

        Schema::create('schedule_recurring_types', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name',20);
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => ReferenceDataSeeder::class,
        ]);

        Artisan::call('db:seed', [
            '--class' => LocationDataSeeder::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('projects');
        Schema::drop('project_media');
        Schema::drop('project_views');
        Schema::drop('project_contacts');
        Schema::drop('project_description_questions');

        Schema::drop('contacts');
        Schema::drop('contact_types');

        Schema::drop('locations');

        Schema::drop('media');
        Schema::drop('media_types');

        Schema::drop('schedules');
        Schema::drop('schedule_recurring_types');
    }
}
