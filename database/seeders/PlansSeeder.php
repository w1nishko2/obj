<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Прораб Старт',
                'slug' => 'free',
                'description' => 'Стартовый тариф для начала работы',
                'price' => 50.00,
                'duration_days' => 30, // 1 месяц
                'is_active' => true,
                'features' => [
                    'max_projects' => 2,
                    'can_create_stages' => true,
                    'can_manage_tasks' => true,
                    'can_add_participants' => true,
                    'can_upload_files' => true,
                    'can_generate_estimates' => false,
                    'can_generate_documents' => false,
                    'can_archive_projects' => false,
                ],
            ],
            [
                'name' => 'Месячная подписка',
                'slug' => 'monthly',
                'description' => 'Статус Прораба на 1 месяц',
                'price' => 2000.00,
                'duration_days' => 30,
                'is_active' => true,
                'features' => [
                    'max_projects' => null, // Неограниченно
                    'can_create_stages' => true,
                    'can_manage_tasks' => true,
                    'can_add_participants' => true,
                    'can_upload_files' => true,
                    'can_generate_estimates' => true,
                    'can_generate_documents' => true,
                    'can_archive_projects' => true,
                    'priority_support' => true,
                ],
            ],
            [
                'name' => 'Годовая подписка',
                'slug' => 'yearly',
                'description' => 'Статус Прораба на 12 месяцев с выгодой 25%',
                'price' => 18000.00,
                'duration_days' => 365,
                'is_active' => true,
                'features' => [
                    'max_projects' => null, // Неограниченно
                    'can_create_stages' => true,
                    'can_manage_tasks' => true,
                    'can_add_participants' => true,
                    'can_upload_files' => true,
                    'can_generate_estimates' => true,
                    'can_generate_documents' => true,
                    'can_archive_projects' => true,
                    'priority_support' => true,
                    'yearly_discount' => true,
                ],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
