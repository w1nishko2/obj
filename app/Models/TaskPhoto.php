<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'photo_path',
        'description',
    ];

    public function task()
    {
        return $this->belongsTo(StageTask::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Получить безопасный URL файла с защитой от path traversal
     */
    public function getSecureUrlAttribute(): string
    {
        // Убираем потенциально опасные последовательности
        $safePath = str_replace(['../', '..\\', '..'], '', $this->photo_path);
        
        // Убеждаемся что путь начинается с разрешенной директории
        if (!str_starts_with($safePath, 'task-photos/')) {
            $safePath = 'task-photos/' . basename($safePath);
        }
        
        return \Illuminate\Support\Facades\Storage::url($safePath);
    }
}
