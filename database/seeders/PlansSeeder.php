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
            // 🆓 БЕСПЛАТНЫЙ ТАРИФ (trial/demo)
            [
                'name' => 'Бесплатный',
                'slug' => 'free',
                'description' => 'Для тестирования системы - 14 дней бесплатно',
                'price' => 0.00,
                'duration_days' => 14,
                'is_active' => true,
                'features' => [
                    'max_projects' => 1,
                    'max_participants' => 5,
                    'can_create_stages' => true,
                    'can_manage_tasks' => true,
                    'can_add_participants' => true,
                    'can_upload_files' => true,
                    'can_generate_estimates' => false,
                    'can_generate_documents' => false,
                    'can_archive_projects' => false,
                    'push_notifications' => true, // Уведомления есть на всех тарифах
                ],
            ],

            // 🥉 СТАРТОВЫЙ ТАРИФ (месячный)
            [
                'name' => 'Стартовый',
                'slug' => 'starter',
                'description' => 'Для прорабов-одиночек (1-3 объекта)',
                'price' => 490.00,
                'duration_days' => 30,
                'is_active' => true,
                'features' => [
                    'max_projects' => 3,
                    'max_participants' => 10,
                    'can_create_stages' => true,
                    'can_manage_tasks' => true,
                    'can_add_participants' => true,
                    'can_upload_files' => true,
                    'can_generate_estimates' => true,
                    'can_generate_documents' => true,
                    'can_archive_projects' => true,
                    'push_notifications' => true,
                ],
            ],

            // 🥉 СТАРТОВЫЙ ТАРИФ (годовой)
            [
                'name' => 'Стартовый (Годовой)',
                'slug' => 'starter_yearly',
                'description' => 'Для прорабов-одиночек - годовая подписка с экономией 980₽',
                'price' => 4900.00,
                'duration_days' => 365,
                'is_active' => true,
                'features' => [
                    'max_projects' => 3,
                    'max_participants' => 10,
                    'can_create_stages' => true,
                    'can_manage_tasks' => true,
                    'can_add_participants' => true,
                    'can_upload_files' => true,
                    'can_generate_estimates' => true,
                    'can_generate_documents' => true,
                    'can_archive_projects' => true,
                    'push_notifications' => true,
                    'yearly_discount' => true,
                ],
            ],

            // 🥈 ПРОФЕССИОНАЛЬНЫЙ ТАРИФ (месячный)
            [
                'name' => 'Профессиональный',
                'slug' => 'professional',
                'description' => 'Для опытных прорабов (4-10 объектов)',
                'price' => 1290.00,
                'duration_days' => 30,
                'is_active' => true,
                'features' => [
                    'max_projects' => 10,
                    'max_participants' => 30,
                    'can_create_stages' => true,
                    'can_manage_tasks' => true,
                    'can_add_participants' => true,
                    'can_upload_files' => true,
                    'can_generate_estimates' => true,
                    'can_generate_documents' => true,
                    'can_archive_projects' => true,
                    'push_notifications' => true,
                    'extended_templates' => true,
                ],
            ],

            // 🥈 ПРОФЕССИОНАЛЬНЫЙ ТАРИФ (годовой)
            [
                'name' => 'Профессиональный (Годовой)',
                'slug' => 'professional_yearly',
                'description' => 'Для опытных прорабов - годовая подписка с экономией 2 580₽',
                'price' => 12900.00,
                'duration_days' => 365,
                'is_active' => true,
                'features' => [
                    'max_projects' => 10,
                    'max_participants' => 30,
                    'can_create_stages' => true,
                    'can_manage_tasks' => true,
                    'can_add_participants' => true,
                    'can_upload_files' => true,
                    'can_generate_estimates' => true,
                    'can_generate_documents' => true,
                    'can_archive_projects' => true,
                    'push_notifications' => true,
                    'extended_templates' => true,
                    'yearly_discount' => true,
                ],
            ],

            // 🥇 КОРПОРАТИВНЫЙ ТАРИФ (месячный)
            [
                'name' => 'Корпоративный',
                'slug' => 'corporate',
                'description' => 'Для компаний (10+ объектов)',
                'price' => 2990.00,
                'duration_days' => 30,
                'is_active' => true,
                'features' => [
                    'max_projects' => null, // Неограниченно
                    'max_participants' => null, // Неограниченно
                    'can_create_stages' => true,
                    'can_manage_tasks' => true,
                    'can_add_participants' => true,
                    'can_upload_files' => true,
                    'can_generate_estimates' => true,
                    'can_generate_documents' => true,
                    'can_archive_projects' => true,
                    'push_notifications' => true,
                    'extended_templates' => true,
                    'multiple_managers' => true,
                    'personal_manager' => true,
                    'support_24_7' => true,
                    'team_training' => true,
                    'custom_features' => true,
                ],
            ],

            // 🥇 КОРПОРАТИВНЫЙ ТАРИФ (годовой)
            [
                'name' => 'Корпоративный (Годовой)',
                'slug' => 'corporate_yearly',
                'description' => 'Для компаний - годовая подписка с экономией 5 980₽',
                'price' => 29900.00,
                'duration_days' => 365,
                'is_active' => true,
                'features' => [
                    'max_projects' => null, // Неограниченно
                    'max_participants' => null, // Неограниченно
                    'can_create_stages' => true,
                    'can_manage_tasks' => true,
                    'can_add_participants' => true,
                    'can_upload_files' => true,
                    'can_generate_estimates' => true,
                    'can_generate_documents' => true,
                    'can_archive_projects' => true,
                    'push_notifications' => true,
                    'extended_templates' => true,
                    'multiple_managers' => true,
                    'personal_manager' => true,
                    'support_24_7' => true,
                    'team_training' => true,
                    'custom_features' => true,
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
