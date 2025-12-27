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
        Schema::create('work_template_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_template_type_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Название этапа (например "Демонтажные работы")
            $table->text('description')->nullable();
            $table->integer('duration_days')->default(1); // Длительность в днях
            $table->integer('order')->default(0); // Порядок сортировки
            $table->timestamps();
            
            $table->index('work_template_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_template_stages');
    }
};
