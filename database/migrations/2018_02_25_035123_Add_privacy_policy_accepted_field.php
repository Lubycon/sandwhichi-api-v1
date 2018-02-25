<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivacyPolicyAcceptedField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->boolean('privacy_policy_accepted')->after('terms_of_service_accepted')->default(0);
        });

        Schema::drop('access_tokens');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('access_tokens', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('token');
            $table->timestamp('expired_at');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('privacy_policy_accepted');
        });
    }
}
