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
        // Используем сырые SQL запросы для изменения колонок без Doctrine DBAL
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NULL');
        
        // Добавляем role, если еще нет
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('Сотрудник')->after('password');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL');
    }
};
