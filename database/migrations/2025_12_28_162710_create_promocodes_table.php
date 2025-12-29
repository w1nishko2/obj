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
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Уникальный код промокода
            $table->integer('discount_percent'); // Процент скидки (0-100)
            $table->boolean('is_active')->default(true); // Активен ли промокод
            $table->integer('usage_limit')->nullable(); // Лимит использований (null = безлимит)
            $table->integer('usage_count')->default(0); // Счетчик использований
            $table->timestamp('expires_at')->nullable(); // Дата истечения
            $table->text('description')->nullable(); // Описание промокода
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promocodes');
    }
};
