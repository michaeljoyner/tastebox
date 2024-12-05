<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('add_on_ordered_kit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_on_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ordered_kit_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('qty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_on_ordered_kit');
    }
};
