<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->integer('week');
            $table->integer('total_kits');
            $table->integer('total_meals');
            $table->integer('total_servings');
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
        Schema::dropIfExists('batch_reports');
    }
}
