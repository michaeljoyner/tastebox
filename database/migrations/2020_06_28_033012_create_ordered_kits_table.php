<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderedKitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordered_kits', function (Blueprint $table) {
            $table->id();
            $table->string('kit_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('menu_id');
            $table->date('delivery_date');
            $table->integer('menu_week_number');
            $table->json('meal_summary');
            $table->string('line_one');
            $table->string('line_two')->nullable();
            $table->string('city');
            $table->string('postal_code');
            $table->text('delivery_notes');
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
        Schema::dropIfExists('ordered_kits');
    }
}
