<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nutrition_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('log_date');
            $table->string('meal_type')->default('dinner'); // breakfast|lunch|dinner|snack
            $table->string('food_name');
            $table->integer('calories')->default(0);
            $table->float('protein_g')->default(0);
            $table->float('carbs_g')->default(0);
            $table->float('fat_g')->default(0);
            $table->integer('water_ml')->default(0);
            $table->timestamps();
            $table->index(['user_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nutrition_logs');
    }
};
