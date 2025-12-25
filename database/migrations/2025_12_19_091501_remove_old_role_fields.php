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
        // Удаляем устаревшее поле role из users
        // Теперь роли хранятся только в project_user_roles
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Удаляем устаревшее поле role из project_participants
        // Роли участников теперь в project_user_roles
        Schema::table('project_participants', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Восстановление для отката
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('Клиент')->after('password');
        });

        Schema::table('project_participants', function (Blueprint $table) {
            $table->enum('role', ['Клиент', 'Исполнитель'])->after('phone');
        });
    }
};
