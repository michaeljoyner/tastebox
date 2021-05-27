<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealTalliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_tallies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meal_id');
            $table->integer('times_offered');
            $table->integer('total_ordered');
            $table->integer('total_servings');
            $table->date('last_offered');
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
        Schema::dropIfExists('meal_tallies');
    }
}
