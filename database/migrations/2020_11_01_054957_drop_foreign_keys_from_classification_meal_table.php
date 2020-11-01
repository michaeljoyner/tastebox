<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeysFromClassificationMealTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classification_meal', function (Blueprint $table) {
//            $table->dropForeign('classification_meal_classification_id_key_foreign');
//            $table->dropForeign('classification_meal_meal_id_key_foreign');
            $table->dropColumn(['classification_id_key', 'meal_id_key']);
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
