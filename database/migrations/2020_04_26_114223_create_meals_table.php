<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('allergens')->nullable();
            $table->unsignedInteger('prep_time')->nullable();
            $table->unsignedInteger('cook_time')->nullable();
            $table->text('instructions')->nullable();
            $table->unsignedInteger('serving_energy')->nullable();
            $table->unsignedInteger('serving_carbs')->nullable();
            $table->unsignedInteger('serving_fat')->nullable();
            $table->unsignedInteger('serving_protein')->nullable();
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
        Schema::dropIfExists('meals');
    }
}
