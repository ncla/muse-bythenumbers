<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voting_ballots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description');
            $table->boolean('is_open');
            // https://github.com/laravel/framework/issues/21912
            $table->timestamp('expires_on')->nullable()->default(null);
            $table->enum('type', ['ranking', 'other']);
            $table->timestamps();
        });

        Schema::create('voting_ballot_songs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('voting_ballot_id');
            $table->integer('song_id');
            $table->unique(['voting_ballot_id', 'song_id']);
        });

        Schema::create('voting_matchups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('voting_ballot_id');
            $table->integer('songA_id');
            $table->integer('songB_id');
            $table->unique(['voting_ballot_id', 'songA_id', 'songB_id']);
        });

        Schema::create('votes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('voting_matchup_id');
            $table->integer('winner_song_id');
            $table->timestamps();
            $table->unique(['user_id', 'voting_matchup_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voting_ballots');
        Schema::dropIfExists('voting_ballot_songs');
        Schema::dropIfExists('votes');
        Schema::dropIfExists('voting_matchups');
    }
}
