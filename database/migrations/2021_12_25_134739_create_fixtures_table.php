<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('home_club_id');
            $table->tinyInteger('home_club_score')->default(0);
            $table->bigInteger('away_club_id');
            $table->tinyInteger('away_club_score')->default(0);
            $table->tinyInteger('week_number');
            $table->tinyInteger('match_play_status')->default(0);
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
        Schema::dropIfExists('fixtures');
    }
}
