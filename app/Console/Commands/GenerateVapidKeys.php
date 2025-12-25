<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webpush:vapid {--show : Показать существующие ключи} {--force : Перегенерировать ключи}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерация VAPID ключей для Web Push уведомлений';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $envPath = base_path('.env');

        if ($this->option('show')) {
            $this->showExistingKeys();
            return 0;
        }

        // Проверка существующих ключей
        if (config('webpush.vapid.public_key') && config('webpush.vapid.private_key') && !$this->option('force')) {
            $this->warn('VAPID ключи уже существуют!');
            $this->info('Публичный ключ: ' . config('webpush.vapid.public_key'));
            
            if (!$this->confirm('Хотите перегенерировать ключи?', false)) {
                $this->info('Операция отменена.');
                return 0;
            }
        }

        try {
            // Генерация ключей
            $vapidKeys = VAPID::createVapidKeys();

            $this->info('✓ VAPID ключи успешно сгенерированы!');
            $this->newLine();
            $this->line('Добавьте следующие строки в ваш .env файл:');
            $this->newLine();
            $this->info('VAPID_PUBLIC_KEY=' . $vapidKeys['publicKey']);
            $this->info('VAPID_PRIVATE_KEY=' . $vapidKeys['privateKey']);
            $this->info('VAPID_SUBJECT=mailto:your-email@example.com');
            $this->newLine();

            // Предложение автоматически добавить в .env
            if ($this->confirm('Хотите автоматически добавить ключи в .env файл?', true)) {
                $this->updateEnvFile($vapidKeys);
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Ошибка при генерации ключей: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Показать существующие ключи
     */
    private function showExistingKeys()
    {
        $publicKey = config('webpush.vapid.public_key');
        $privateKey = config('webpush.vapid.private_key');
        $subject = config('webpush.vapid.subject');

        if (!$publicKey || !$privateKey) {
            $this->warn('VAPID ключи не настроены!');
            $this->info('Запустите команду без опции --show для генерации новых ключей.');
            return;
        }

        $this->info('Текущие VAPID ключи:');
        $this->newLine();
        $this->line('Публичный ключ:  ' . $publicKey);
        $this->line('Приватный ключ:  ' . str_repeat('*', strlen($privateKey) - 10) . substr($privateKey, -10));
        $this->line('Subject:         ' . $subject);
    }

    /**
     * Обновить .env файл
     */
    private function updateEnvFile(array $vapidKeys)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            $this->error('.env файл не найден!');
            return;
        }

        $envContent = file_get_contents($envPath);

        // Удаляем старые ключи если есть
        $envContent = preg_replace('/^VAPID_PUBLIC_KEY=.*$/m', '', $envContent);
        $envContent = preg_replace('/^VAPID_PRIVATE_KEY=.*$/m', '', $envContent);
        
        // Если VAPID_SUBJECT не существует, добавляем его
        if (!preg_match('/^VAPID_SUBJECT=/m', $envContent)) {
            $envContent = preg_replace('/^VAPID_PRIVATE_KEY=.*$/m', '', $envContent);
        }

        // Добавляем новые ключи в конец файла
        $envContent = rtrim($envContent) . "\n\n";
        $envContent .= "# Web Push VAPID Keys\n";
        $envContent .= "VAPID_PUBLIC_KEY={$vapidKeys['publicKey']}\n";
        $envContent .= "VAPID_PRIVATE_KEY={$vapidKeys['privateKey']}\n";
        
        if (!config('webpush.vapid.subject') || config('webpush.vapid.subject') === 'mailto:admin@example.com') {
            $envContent .= "VAPID_SUBJECT=mailto:admin@example.com\n";
        }

        file_put_contents($envPath, $envContent);

        $this->info('✓ Ключи успешно добавлены в .env файл!');
        $this->warn('⚠ Не забудьте изменить VAPID_SUBJECT на ваш email!');
        $this->info('Выполните: php artisan config:clear');
    }
}
