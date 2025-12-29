<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $imagePath;
    protected $disk;

    /**
     * Create a new job instance.
     */
    public function __construct(string $imagePath, string $disk = 'public')
    {
        $this->imagePath = $imagePath;
        $this->disk = $disk;
        
        // Устанавливаем отдельную очередь для обработки изображений
        $this->onQueue('images');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Получаем полный путь к файлу
        $fullPath = Storage::disk($this->disk)->path($this->imagePath);
        
        if (!file_exists($fullPath)) {
            \Log::error("Image not found: {$fullPath}");
            return;
        }

        try {
            // Создаем ImageManager с GD драйвером
            $manager = new ImageManager(new Driver());
            
            // Загружаем изображение
            $image = $manager->read($fullPath);
            
            // Изменяем путь на .webp
            $webpPath = preg_replace('/\.(jpg|jpeg|png|gif|bmp)$/i', '.webp', $this->imagePath);
            $webpFullPath = Storage::disk($this->disk)->path($webpPath);
            
            // Сохраняем в формате WebP с качеством 85 (хороший баланс)
            $image->toWebp(85)->save($webpFullPath);
            
            // Если расширение изменилось, удаляем оригинал
            if ($webpPath !== $this->imagePath) {
                Storage::disk($this->disk)->delete($this->imagePath);
                
                // Обновляем путь в базе данных
                $this->updateDatabasePath($this->imagePath, $webpPath);
            }
            
            \Log::info("Image processed: {$this->imagePath} -> {$webpPath}");
        } catch (\Exception $e) {
            \Log::error("Failed to process image {$this->imagePath}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Обновляем путь к файлу в базе данных
     */
    protected function updateDatabasePath(string $oldPath, string $newPath): void
    {
        // Обновляем TaskPhoto
        \App\Models\TaskPhoto::where('photo_path', $oldPath)->update(['photo_path' => $newPath]);
        
        // Обновляем ProjectDocument (если это изображение)
        \App\Models\ProjectDocument::where('file_path', $oldPath)->update([
            'file_path' => $newPath,
            'file_type' => 'image/webp',
        ]);
    }
}
