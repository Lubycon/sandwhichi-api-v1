<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccessTokensTable extends Migration {

	public function up()
	{
		Schema::create('access_tokens', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('token');
			$table->timestamp('expired_at');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('access_tokens');
	}
}