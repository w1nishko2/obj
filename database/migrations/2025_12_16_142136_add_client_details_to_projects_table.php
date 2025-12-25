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
            // Данные клиента
            $table->string('client_full_name')->nullable()->after('address');
            $table->string('client_phone')->nullable()->after('client_full_name');
            $table->string('client_email')->nullable()->after('client_phone');
            $table->text('client_address')->nullable()->after('client_email');
            
            // Паспортные данные клиента
            $table->string('client_passport_series')->nullable()->after('client_address');
            $table->string('client_passport_number')->nullable()->after('client_passport_series');
            $table->string('client_passport_issued_by')->nullable()->after('client_passport_number');
            $table->date('client_passport_issued_date')->nullable()->after('client_passport_issued_by');
            
            // ИНН клиента (для юр. лиц)
            $table->string('client_inn')->nullable()->after('client_passport_issued_date');
            $table->string('client_organization_name')->nullable()->after('client_inn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'client_full_name',
                'client_phone',
                'client_email',
                'client_address',
                'client_passport_series',
                'client_passport_number',
                'client_passport_issued_by',
                'client_passport_issued_date',
                'client_inn',
                'client_organization_name',
            ]);
        });
    }
};
