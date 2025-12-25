<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stage_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained('project_stages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit')->default('шт'); // шт, м, кг, л и т.д.
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('price_per_unit', 12, 2);
            $table->decimal('total_cost', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stage_materials');
    }
};
