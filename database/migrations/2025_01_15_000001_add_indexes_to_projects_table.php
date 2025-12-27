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
        Schema::table('projects', function (Blueprint $table) {
            // Индекс для быстрого поиска проектов пользователя
            $table->index('user_id');
            
            // Композитный индекс для фильтрации по архивным/активным проектам
            $table->index(['user_id', 'is_archived']);
            
            // Индекс для фильтрации по статусу
            $table->index('status');
            
            // Композитный индекс для производительности при множественных условиях
            $table->index(['user_id', 'status', 'is_archived']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['user_id', 'is_archived']);
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status', 'is_archived']);
        });
    }
};
