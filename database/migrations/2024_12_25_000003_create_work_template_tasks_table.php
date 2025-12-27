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
        Schema::create('work_template_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_template_stage_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Название задачи
            $table->text('description')->nullable();
            $table->integer('duration_days')->default(1); // Длительность в днях
            $table->decimal('price', 10, 2)->default(0); // Цена задачи
            $table->integer('order')->default(0); // Порядок сортировки
            $table->timestamps();
            
            $table->index('work_template_stage_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_template_tasks');
    }
};
