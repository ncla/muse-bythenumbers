<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_logins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('browser_useragent');
            $table->ipAddress('ip_address');
            $table->timestamp('created_at');

            $table->unique(['ip_address', 'user_id'], 'ip_address_user_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_logins');
    }
}
