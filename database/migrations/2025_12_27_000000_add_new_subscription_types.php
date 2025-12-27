<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Расширяем ENUM для subscription_type - добавляем новые тарифы
        DB::statement("ALTER TABLE users MODIFY COLUMN subscription_type ENUM(
            'free',
            'starter', 'starter_yearly',
            'professional', 'professional_yearly',
            'corporate', 'corporate_yearly',
            'monthly', 'yearly'
        ) NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Возвращаем обратно старые значения ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN subscription_type ENUM('free','monthly','yearly') NULL DEFAULT NULL");
    }
};
