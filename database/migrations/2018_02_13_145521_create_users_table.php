<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->string('email');
			$table->string('password', 500);
			$table->string('status', 100)->default('inactive');
			$table->string('first_name', 10)->nullable();
			$table->string('last_name', 10)->nullable();
			$table->string('introduce', 500)->nullable();
			$table->boolean('email_accepted')->default(false);
			$table->boolean('terms_of_service_accepted')->default(false);
			$table->timestamp('last_signin_time')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}