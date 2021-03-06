<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesForMealIngredientPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ingredient_meal', function (Blueprint $table) {
            $table->unsignedBigInteger('ingredient_id')->change();
            $table->unsignedBigInteger('meal_id')->change();

            $table->foreign('ingredient_id')->references('id')->on('ingredients');
            $table->foreign('meal_id')->references('id')->on('meals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ingredient_meal', function (Blueprint $table) {
            //
        });
    }
}
