<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Прораб Старт, Месячная подписка, Годовая подписка
            $table->string('slug')->unique(); // free, monthly, yearly
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0); // Цена в рублях
            $table->integer('duration_days')->nullable(); // Длительность в днях (null = бессрочно)
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable(); // JSON с возможностями
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
