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
        Schema::create('stage_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained('project_stages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit', 50); // шт, литры, м³, рейсы и т.д.
            $table->decimal('quantity', 10, 2);
            $table->decimal('price_per_unit', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stage_deliveries');
    }
};
