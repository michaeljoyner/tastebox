<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToClassificationMealTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classification_meal', function (Blueprint $table) {
            $table->unsignedBigInteger('classification_id')->change();
            $table->unsignedBigInteger('meal_id')->change();

            $table->foreign('classification_id')->references('id')->on('classifications');
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
        Schema::table('classification_meal', function (Blueprint $table) {
            //
        });
    }
}
