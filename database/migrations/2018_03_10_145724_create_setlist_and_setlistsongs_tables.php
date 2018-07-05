<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetlistAndSetlistsongsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setlists', function(Blueprint $table) {
            $table->string('id', 50)->unique();
            $table->date('date');
            $table->json('venue');
            $table->string('url', 2000);
            $table->boolean('is_utilized')->default('1');

            $table->primary('id');
        });

        Schema::create('setlist_songs', function(Blueprint $table) {
            $table->string('id', 50);
            $table->string('name', 256);
            $table->boolean('tape');
            $table->integer('encore');
            $table->string('note')->nullable();
            $table->integer('order_nr_in_set');
            $table->integer('order_nr_overall');

            $table->index(['id', 'order_nr_overall', 'name'], 'songs_order_nr_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('setlists');
        Schema::drop('setlist_songs');
    }
}
