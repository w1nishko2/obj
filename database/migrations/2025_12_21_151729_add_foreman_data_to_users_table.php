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
            // Основная информация прораба
            $table->string('full_name')->nullable()->after('phone');
            $table->text('address')->nullable()->after('full_name');
            
            // Паспортные данные
            $table->string('passport_series', 10)->nullable()->after('address');
            $table->string('passport_number', 20)->nullable()->after('passport_series');
            $table->string('passport_issued_by')->nullable()->after('passport_number');
            $table->date('passport_issued_date')->nullable()->after('passport_issued_by');
            
            // Данные для юр. лиц
            $table->string('inn', 12)->nullable()->after('passport_issued_date');
            $table->string('organization_name')->nullable()->after('inn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'full_name',
                'address',
                'passport_series',
                'passport_number',
                'passport_issued_by',
                'passport_issued_date',
                'inn',
                'organization_name',
            ]);
        });
    }
};
