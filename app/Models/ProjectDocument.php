<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'uploaded_by',
        'name',
        'file_path',
        'file_type',
        'file_size',
        'description',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes < 1024) {
            return $bytes . ' Б';
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 2) . ' КБ';
        } else {
            return round($bytes / 1048576, 2) . ' МБ';
        }
    }

    public function getFileIconAttribute(): string
    {
        $extension = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
        
        return match($extension) {
            'pdf' => 'bi-file-pdf',
            'doc', 'docx' => 'bi-file-word',
            'xls', 'xlsx' => 'bi-file-excel',
            'jpg', 'jpeg', 'png', 'gif', 'svg' => 'bi-file-image',
            'zip', 'rar', '7z' => 'bi-file-zip',
            'dwg', 'dxf' => 'bi-file-earmark-ruled',
            default => 'bi-file-earmark',
        };
    }
}
