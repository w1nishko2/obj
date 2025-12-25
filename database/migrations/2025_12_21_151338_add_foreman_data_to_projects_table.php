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
            // Основная информация прораба
            $table->string('foreman_full_name')->nullable()->after('client_organization_name');
            $table->string('foreman_phone', 20)->nullable()->after('foreman_full_name');
            $table->string('foreman_email')->nullable()->after('foreman_phone');
            $table->text('foreman_address')->nullable()->after('foreman_email');
            
            // Паспортные данные прораба
            $table->string('foreman_passport_series', 10)->nullable()->after('foreman_address');
            $table->string('foreman_passport_number', 20)->nullable()->after('foreman_passport_series');
            $table->string('foreman_passport_issued_by')->nullable()->after('foreman_passport_number');
            $table->date('foreman_passport_issued_date')->nullable()->after('foreman_passport_issued_by');
            
            // Данные для юр. лиц (прораб)
            $table->string('foreman_inn', 12)->nullable()->after('foreman_passport_issued_date');
            $table->string('foreman_organization_name')->nullable()->after('foreman_inn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'foreman_full_name',
                'foreman_phone',
                'foreman_email',
                'foreman_address',
                'foreman_passport_series',
                'foreman_passport_number',
                'foreman_passport_issued_by',
                'foreman_passport_issued_date',
                'foreman_inn',
                'foreman_organization_name',
            ]);
        });
    }
};
