<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMatchUpServeMethodColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voting_ballots', function (Blueprint $table) {
            $table->tinyInteger('matchup_serve_method')->after('is_open')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voting_ballots', function (Blueprint $table) {
            $table->dropColumn('matchup_serve_method');
        });
    }
}
