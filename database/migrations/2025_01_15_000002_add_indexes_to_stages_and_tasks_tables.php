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
        // Индексы для project_stages
        Schema::table('project_stages', function (Blueprint $table) {
            $table->index('project_id');
            $table->index(['project_id', 'order']); // Для сортировки этапов
            $table->index('status'); // Для фильтрации по статусу
        });

        // Индексы для stage_tasks
        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->index('stage_id');
            $table->index('assigned_to'); // Для поиска задач пользователя
            $table->index(['stage_id', 'order']); // Для сортировки задач в этапе
            $table->index('status'); // Для фильтрации по статусу
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_stages', function (Blueprint $table) {
            $table->dropIndex(['project_id']);
            $table->dropIndex(['project_id', 'order']);
            $table->dropIndex(['status']);
        });

        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->dropIndex(['stage_id']);
            $table->dropIndex(['assigned_to']);
            $table->dropIndex(['stage_id', 'order']);
            $table->dropIndex(['status']);
        });
    }
};
