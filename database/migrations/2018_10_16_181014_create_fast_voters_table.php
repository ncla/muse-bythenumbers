<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFastVotersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fast_voters', function (Blueprint $table) {
            $table->increments('id');
            $table->float('time_between_votes');
            $table->float('time_between_votes_client')->nullable();
            $table->string('browser_useragent');
            $table->integer('user_id');
            $table->ipAddress('ip_address');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fast_voters');
    }
}
