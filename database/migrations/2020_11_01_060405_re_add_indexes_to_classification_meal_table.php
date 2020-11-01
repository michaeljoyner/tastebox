<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReAddIndexesToClassificationMealTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classification_meal', function (Blueprint $table) {
            $table->foreign('classification_id')
                  ->references('id')
                  ->on('classifications')
                  ->onDelete('cascade');

            $table->foreign('meal_id')
                  ->references('id')
                  ->on('meals')
                  ->onDelete('cascade');
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
