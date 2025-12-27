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
        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('cost');
            $table->integer('duration_days')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->dropColumn(['price', 'duration_days']);
        });
    }
};
