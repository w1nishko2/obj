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
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название шаблона (Смета, Договор, Акт выполненных работ и т.д.)
            $table->string('type'); // Тип документа: estimate, contract, act, receipt и т.д.
            $table->text('description')->nullable(); // Описание шаблона
            $table->text('content'); // Содержимое шаблона с плейсхолдерами
            $table->boolean('is_active')->default(true); // Активен ли шаблон
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
