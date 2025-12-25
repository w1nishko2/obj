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
        Schema::create('project_user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['owner', 'client', 'executor'])->comment('owner = владелец/прораб проекта, client = клиент, executor = исполнитель');
            
            // Гибкие права доступа
            $table->boolean('can_edit_project')->default(false)->comment('Может редактировать настройки проекта');
            $table->boolean('can_manage_team')->default(false)->comment('Может управлять командой (добавлять/удалять участников)');
            $table->boolean('can_create_stages')->default(false)->comment('Может создавать этапы');
            $table->boolean('can_edit_tasks')->default(false)->comment('Может редактировать задачи');
            $table->boolean('can_view_costs')->default(false)->comment('Может видеть стоимость');
            $table->boolean('can_upload_documents')->default(false)->comment('Может загружать документы');
            $table->boolean('can_generate_reports')->default(false)->comment('Может генерировать отчёты');
            
            $table->timestamps();
            
            // Один пользователь может быть только в одной роли в проекте
            $table->unique(['project_id', 'user_id']);
            
            // Индексы для быстрого поиска
            $table->index('user_id');
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user_roles');
    }
};
