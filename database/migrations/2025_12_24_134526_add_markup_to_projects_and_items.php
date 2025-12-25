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
        // Добавляем наценку в проекты (общая наценка проекта)
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('markup_percent', 5, 2)->nullable()->after('is_archived')->comment('Общая наценка проекта в процентах');
        });

        // Добавляем наценку в задачи
        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->decimal('markup_percent', 5, 2)->nullable()->after('cost')->comment('Наценка для задачи в процентах');
        });

        // Добавляем наценку в материалы
        Schema::table('stage_materials', function (Blueprint $table) {
            $table->decimal('markup_percent', 5, 2)->nullable()->after('total_cost')->comment('Наценка для материала в процентах');
        });

        // Добавляем наценку в доставки
        Schema::table('stage_deliveries', function (Blueprint $table) {
            $table->decimal('markup_percent', 5, 2)->nullable()->after('total_cost')->comment('Наценка для доставки в процентах');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('markup_percent');
        });

        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->dropColumn('markup_percent');
        });

        Schema::table('stage_materials', function (Blueprint $table) {
            $table->dropColumn('markup_percent');
        });

        Schema::table('stage_deliveries', function (Blueprint $table) {
            $table->dropColumn('markup_percent');
        });
    }
};
