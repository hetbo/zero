<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('carrotables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrot_id')->constrained()->cascadeOnDelete();
            $table->morphs('carrotable'); // Creates `carrotable_id` & `carrotable_type`
            $table->string('role');       // The context: 'ingredient', 'salad', 'product'

            $table->unique(['carrot_id', 'carrotable_id', 'carrotable_type', 'role']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('carrotables');
    }
};