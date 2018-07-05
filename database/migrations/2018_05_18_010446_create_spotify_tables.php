<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpotifyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_albums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('album_id')->unique();
            $table->string('album_name');
            $table->timestamp('release_date')->nullable()->default(null);
            $table->enum('album_type', ['album', 'single']);
            $table->string('image_url');
            $table->integer('image_width');
            $table->integer('image_height');
            $table->timestamps();
        });

        Schema::create('spotify_tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('track_id')->unique();
            $table->string('track_name');
            $table->integer('track_number');
            $table->string('album_id');
            $table->integer('duration_ms');
            $table->string('preview_url_mp3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spotify_albums');
        Schema::dropIfExists('spotify_tracks');
    }
}
