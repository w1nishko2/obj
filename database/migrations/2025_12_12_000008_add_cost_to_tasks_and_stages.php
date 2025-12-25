<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->decimal('cost', 12, 2)->default(0)->after('status');
        });

        Schema::table('project_stages', function (Blueprint $table) {
            $table->decimal('budget', 12, 2)->nullable()->after('order');
        });
    }

    public function down(): void
    {
        Schema::table('stage_tasks', function (Blueprint $table) {
            $table->dropColumn('cost');
        });

        Schema::table('project_stages', function (Blueprint $table) {
            $table->dropColumn('budget');
        });
    }
};
