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
        Schema::table('users', function (Blueprint $table) {
            // Тип аккаунта: клиент (только просмотр) или прораб (может создавать проекты)
            $table->enum('account_type', ['client', 'foreman'])->default('client')->after('password');
        });
        
        // Устанавливаем 'foreman' всем, у кого есть проекты
        DB::statement("
            UPDATE users 
            SET account_type = 'foreman' 
            WHERE id IN (SELECT DISTINCT user_id FROM project_user_roles WHERE role = 'owner')
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('account_type');
        });
    }
};
