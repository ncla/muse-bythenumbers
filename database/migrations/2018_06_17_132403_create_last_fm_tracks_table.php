<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLastFmTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lastfm_tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chart_index');
            // https://stackoverflow.com/questions/41102371/sql-doesnt-differentiate-u-and-%C3%BC-although-collation-is-utf8mb4-unicode-ci
            $table->string('track_name')->collation('utf8mb4_bin');
            $table->integer('listeners_week');
            $table->timestamps();
            $table->unique(['track_name', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lastfm_tracks');
    }
}
