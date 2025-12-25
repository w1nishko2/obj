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
}
