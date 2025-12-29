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
        // Сначала конвертируем строковые значения в boolean
        DB::statement("UPDATE users SET role_selected = CASE 
            WHEN role_selected IN ('foreman', 'executor', 'client') THEN 1 
            ELSE 0 
        END");

        // Теперь меняем тип колонки
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('role_selected')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role_selected', 50)->nullable()->change();
        });
    }
};
