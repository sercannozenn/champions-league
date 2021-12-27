<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('goalkeeper_effect')->default(1);
            $table->tinyInteger('home_team_effect')->default(1);
            $table->tinyInteger('fan_effect')->default(1);
            $table->tinyInteger('weather_effect')->default(1);
            $table->tinyInteger('striker_effect')->default(1);
            $table->tinyInteger('winner_effect')->default(1);
            $table->integer('p')->default(0);
            $table->integer('w')->default(0);
            $table->integer('d')->default(0);
            $table->integer('l')->default(0);
            $table->integer('gd')->default(0);
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
        Schema::dropIfExists('clubs');
    }
}
