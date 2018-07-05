<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusicbrainzSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musicbrainz_songs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mbid', 128)->nullable();
            $table->string('name', 256);
            $table->string('name_override')->nullable();
            $table->string('name_spotify_override')->nullable();
            $table->string('name_lastfm_override')->nullable();
            $table->string('name_setlistfm_override')->nullable();
            $table->boolean('manually_added')->default(false);
            $table->boolean('is_utilized')->default(true);
            $table->unique(['mbid', 'name']);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('musicbrainz_songs');
    }
}
