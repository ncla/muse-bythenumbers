<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopSpotifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_top_tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('track_id');
            $table->integer('chart_index');
            $table->timestamps();

            $table->unique(['track_id', 'created_at', 'chart_index']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spotify_top_tracks');
    }
}
