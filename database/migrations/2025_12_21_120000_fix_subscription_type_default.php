<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Изменяем колонку subscription_type - убираем default, делаем nullable
        DB::statement("ALTER TABLE users MODIFY COLUMN subscription_type ENUM('free','monthly','yearly') NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Возвращаем обратно default='free'
        DB::statement("ALTER TABLE users MODIFY COLUMN subscription_type ENUM('free','monthly','yearly') NOT NULL DEFAULT 'free'");
    }
};
