<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotingBallotCalculationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voting_ballot_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('voting_ballot_id');
            $table->boolean('public');
            $table->timestamp('created_at');
        });

        Schema::create('voting_ballot_song_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('voting_results_id');
            $table->integer('song_id');

            $table->integer('total_votes');
            $table->integer('votes_won');
            $table->integer('votes_lost');
            $table->float('winrate');
            $table->float('elo_rank');

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
        Schema::dropIfExists('voting_ballot_results');
        Schema::dropIfExists('voting_ballot_song_results');
    }
}
