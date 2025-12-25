<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectStage;
use App\Models\ProjectParticipant;
use App\Models\StageTask;
use App\Models\StageMaterial;
use App\Models\TaskComment;
use App\Models\TaskPhoto;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем пользователей с разными ролями
        $foreman = User::updateOrCreate(
            ['email' => 'foreman@example.com'],
            [
                'name' => 'Иван Петров',
                'phone' => '+7 (495) 111-11-11',
                'password' => bcrypt('password'),
                'role' => 'Прораб',
            ]
        );

        $client = User::updateOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'Мария Сидорова',
                'phone' => '+7 (495) 123-45-67',
                'password' => bcrypt('password'),
                'role' => 'Клиент',
            ]
        );

        $employee1 = User::updateOrCreate(
            ['email' => 'employee1@example.com'],
            [
                'name' => 'Алексей Иванов',
                'phone' => '+7 (916) 234-56-78',
                'password' => bcrypt('password'),
                'role' => 'Сотрудник',
            ]
        );

        $employee2 = User::updateOrCreate(
            ['email' => 'employee2@example.com'],
            [
                'name' => 'Дмитрий Смирнов',
                'phone' => '+7 (916) 345-67-89',
                'password' => bcrypt('password'),
                'role' => 'Сотрудник',
            ]
        );

        $employee3 = User::updateOrCreate(
            ['email' => 'employee3@example.com'],
            [
                'name' => 'Сергей Кузнецов',
                'phone' => '+7 (916) 456-78-90',
                'password' => bcrypt('password'),
                'role' => 'Сотрудник',
            ]
        );

        $user = $foreman;

        // ========================================================
        // ДЕТАЛЬНО ЗАПОЛНЕННЫЙ ПРОЕКТ: Ремонт 3-комнатной квартиры
        // ========================================================
        $project = $user->projects()->create([
            'name' => 'Капитальный ремонт 3-комнатной квартиры на ул. Ленина, 42',
            'address' => 'г. Москва, ул. Ленина, д. 42, кв. 125',
            'work_type' => 'Ремонт',
            'status' => 'В работе',
        ]);

        // Добавляем участников проекта с привязкой к пользователям
        $project->participants()->createMany([
            ['name' => 'Мария Сидорова (Заказчик)', 'phone' => '+7 (495) 123-45-67', 'role' => 'Клиент', 'user_id' => $client->id],
            ['name' => 'Алексей Иванов (Электрик)', 'phone' => '+7 (916) 234-56-78', 'role' => 'Исполнитель', 'user_id' => $employee1->id],
            ['name' => 'Дмитрий Смирнов (Сантехник)', 'phone' => '+7 (916) 345-67-89', 'role' => 'Исполнитель', 'user_id' => $employee2->id],
            ['name' => 'Сергей Кузнецов (Отделочник)', 'phone' => '+7 (916) 456-78-90', 'role' => 'Исполнитель', 'user_id' => $employee3->id],
            ['name' => 'Андрей Волков (Плиточник)', 'phone' => '+7 (916) 567-89-01', 'role' => 'Исполнитель'], // Без аккаунта
        ]);

        // ========================================================
        // ЭТАП 1: Демонтаж старых покрытий (Завершен)
        // ========================================================
        $stage1 = $project->stages()->create([
            'name' => 'Демонтаж старых покрытий',
            'start_date' => Carbon::now()->subDays(50),
            'end_date' => Carbon::now()->subDays(45),
            'status' => 'Готово',
            'order' => 0,
        ]);

        // Задачи этапа 1
        $task1_1 = $stage1->tasks()->create([
            'name' => 'Демонтаж старых обоев',
            'description' => 'Снять обои во всех комнатах, очистить поверхность стен',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Завершена',
            'cost' => 8000.00,
            'order' => 0,
        ]);
        $task1_1->comments()->createMany([
            ['user_id' => $employee3->id, 'comment' => 'Начал демонтаж в гостиной', 'created_at' => Carbon::now()->subDays(50)],
            ['user_id' => $employee3->id, 'comment' => 'Закончил все комнаты, поверхность готова к штукатурке', 'created_at' => Carbon::now()->subDays(49)],
            ['user_id' => $foreman->id, 'comment' => 'Отличная работа, все сделано качественно', 'created_at' => Carbon::now()->subDays(48)],
        ]);

        $task1_2 = $stage1->tasks()->create([
            'name' => 'Снятие старого линолеума',
            'description' => 'Демонтаж линолеума, очистка основания',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Завершена',
            'cost' => 5000.00,
            'order' => 1,
        ]);
        $task1_2->comments()->create([
            'user_id' => $employee3->id, 
            'comment' => 'Линолеум снят, основание очищено и подготовлено под стяжку',
            'created_at' => Carbon::now()->subDays(48)
        ]);

        $task1_3 = $stage1->tasks()->create([
            'name' => 'Демонтаж старой плитки в санузлах',
            'description' => 'Снять плитку в ванной и туалете, подготовить стены',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Завершена',
            'cost' => 12000.00,
            'order' => 2,
        ]);
        $task1_3->comments()->createMany([
            ['user_id' => $employee3->id, 'comment' => 'Работа трудоемкая, плитка была на цементе', 'created_at' => Carbon::now()->subDays(47)],
            ['user_id' => $foreman->id, 'comment' => 'Понимаю, главное качественно', 'created_at' => Carbon::now()->subDays(47)],
        ]);

        // ========================================================
        // ЭТАП 2: Черновая электрика (Завершен)
        // ========================================================
        $stage2 = $project->stages()->create([
            'name' => 'Черновая электрика',
            'start_date' => Carbon::now()->subDays(44),
            'end_date' => Carbon::now()->subDays(37),
            'status' => 'Готово',
            'order' => 1,
        ]);

        $task2_1 = $stage2->tasks()->create([
            'name' => 'Разметка электроточек',
            'description' => 'Разметить расположение розеток, выключателей, светильников согласно проекту',
            'created_by' => $foreman->id,
            'assigned_to' => $employee1->id,
            'status' => 'Завершена',
            'cost' => 3000.00,
            'order' => 0,
        ]);
        $task2_1->comments()->createMany([
            ['user_id' => $employee1->id, 'comment' => 'Сделал разметку согласно чертежам', 'created_at' => Carbon::now()->subDays(44)],
            ['user_id' => $client->id, 'comment' => 'Прошу добавить еще 2 розетки на кухне', 'created_at' => Carbon::now()->subDays(44)],
            ['user_id' => $foreman->id, 'comment' => 'Добавим, без проблем', 'created_at' => Carbon::now()->subDays(44)],
        ]);

        $task2_2 = $stage2->tasks()->create([
            'name' => 'Штробление стен под проводку',
            'description' => 'Проштробить каналы для кабелей во всех помещениях',
            'created_by' => $foreman->id,
            'assigned_to' => $employee1->id,
            'status' => 'Завершена',
            'cost' => 15000.00,
            'order' => 1,
        ]);
        $task2_2->comments()->create([
            'user_id' => $employee1->id, 
            'comment' => 'Штробление завершено, готов к прокладке кабеля',
            'created_at' => Carbon::now()->subDays(42)
        ]);

        $task2_3 = $stage2->tasks()->create([
            'name' => 'Прокладка кабелей',
            'description' => 'Проложить кабели ВВГ-3х2.5 для розеток, ВВГ-3х1.5 для освещения',
            'created_by' => $foreman->id,
            'assigned_to' => $employee1->id,
            'status' => 'Завершена',
            'cost' => 25000.00,
            'order' => 2,
        ]);
        $task2_3->comments()->createMany([
            ['user_id' => $employee1->id, 'comment' => 'Кабели проложены, все подписано', 'created_at' => Carbon::now()->subDays(40)],
            ['user_id' => $foreman->id, 'comment' => 'Проверил, все отлично', 'created_at' => Carbon::now()->subDays(39)],
        ]);

        $task2_4 = $stage2->tasks()->create([
            'name' => 'Установка подрозетников',
            'description' => 'Установить подрозетники во всех точках',
            'created_by' => $foreman->id,
            'assigned_to' => $employee1->id,
            'status' => 'Завершена',
            'cost' => 8000.00,
            'order' => 3,
        ]);

        // Материалы для электрики
        $stage2->materials()->createMany([
            [
                'user_id' => $foreman->id,
                'name' => 'Кабель ВВГ-3х2.5',
                'description' => 'Для розеточных групп',
                'unit' => 'м',
                'quantity' => 150,
                'price_per_unit' => 65.00,
                'total_cost' => 9750.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Кабель ВВГ-3х1.5',
                'description' => 'Для освещения',
                'unit' => 'м',
                'quantity' => 100,
                'price_per_unit' => 45.00,
                'total_cost' => 4500.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Подрозетники',
                'description' => 'Глубокие, для бетона',
                'unit' => 'шт',
                'quantity' => 45,
                'price_per_unit' => 35.00,
                'total_cost' => 1575.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Распределительные коробки',
                'description' => 'Для соединений',
                'unit' => 'шт',
                'quantity' => 12,
                'price_per_unit' => 55.00,
                'total_cost' => 660.00,
            ],
        ]);

        // ========================================================
        // ЭТАП 3: Черновая сантехника (Завершен)
        // ========================================================
        $stage3 = $project->stages()->create([
            'name' => 'Черновая сантехника',
            'start_date' => Carbon::now()->subDays(36),
            'end_date' => Carbon::now()->subDays(30),
            'status' => 'Готово',
            'order' => 2,
        ]);

        $task3_1 = $stage3->tasks()->create([
            'name' => 'Разводка труб холодной воды',
            'description' => 'Проложить трубы ХВС к точкам водоразбора',
            'created_by' => $foreman->id,
            'assigned_to' => $employee2->id,
            'status' => 'Завершена',
            'cost' => 18000.00,
            'order' => 0,
        ]);
        $task3_1->comments()->createMany([
            ['user_id' => $employee2->id, 'comment' => 'Использую полипропиленовые трубы армированные', 'created_at' => Carbon::now()->subDays(36)],
            ['user_id' => $foreman->id, 'comment' => 'Правильно, они надежнее', 'created_at' => Carbon::now()->subDays(36)],
            ['user_id' => $employee2->id, 'comment' => 'Разводка ХВС завершена, проверил на протечки', 'created_at' => Carbon::now()->subDays(34)],
        ]);

        $task3_2 = $stage3->tasks()->create([
            'name' => 'Разводка труб горячей воды',
            'description' => 'Проложить трубы ГВС к точкам водоразбора',
            'created_by' => $foreman->id,
            'assigned_to' => $employee2->id,
            'status' => 'Завершена',
            'cost' => 18000.00,
            'order' => 1,
        ]);
        $task3_2->comments()->create([
            'user_id' => $employee2->id, 
            'comment' => 'ГВС проложено, все точки подключены',
            'created_at' => Carbon::now()->subDays(33)
        ]);

        $task3_3 = $stage3->tasks()->create([
            'name' => 'Замена канализационных труб',
            'description' => 'Установить новые канализационные трубы 110мм и 50мм',
            'created_by' => $foreman->id,
            'assigned_to' => $employee2->id,
            'status' => 'Завершена',
            'cost' => 22000.00,
            'order' => 2,
        ]);
        $task3_3->comments()->createMany([
            ['user_id' => $employee2->id, 'comment' => 'Старые трубы демонтированы', 'created_at' => Carbon::now()->subDays(32)],
            ['user_id' => $employee2->id, 'comment' => 'Новые трубы установлены, проверил уклоны', 'created_at' => Carbon::now()->subDays(31)],
        ]);

        $task3_4 = $stage3->tasks()->create([
            'name' => 'Установка счетчиков воды',
            'description' => 'Установить счетчики ХВС и ГВС',
            'created_by' => $foreman->id,
            'assigned_to' => $employee2->id,
            'status' => 'Завершена',
            'cost' => 5000.00,
            'order' => 3,
        ]);

        // Материалы для сантехники
        $stage3->materials()->createMany([
            [
                'user_id' => $foreman->id,
                'name' => 'Трубы полипропиленовые армированные 25мм',
                'description' => 'Для ХВС и ГВС',
                'unit' => 'м',
                'quantity' => 40,
                'price_per_unit' => 120.00,
                'total_cost' => 4800.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Трубы канализационные 110мм',
                'description' => 'Основная канализация',
                'unit' => 'м',
                'quantity' => 15,
                'price_per_unit' => 180.00,
                'total_cost' => 2700.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Трубы канализационные 50мм',
                'description' => 'Отводы к сантехнике',
                'unit' => 'м',
                'quantity' => 20,
                'price_per_unit' => 95.00,
                'total_cost' => 1900.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Счетчики воды',
                'description' => 'ХВС и ГВС',
                'unit' => 'компл',
                'quantity' => 1,
                'price_per_unit' => 3500.00,
                'total_cost' => 3500.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Фитинги и переходники',
                'description' => 'Углы, тройники, муфты',
                'unit' => 'компл',
                'quantity' => 1,
                'price_per_unit' => 2800.00,
                'total_cost' => 2800.00,
            ],
        ]);

        // ========================================================
        // ЭТАП 4: Штукатурка стен (Завершен)
        // ========================================================
        $stage4 = $project->stages()->create([
            'name' => 'Штукатурка стен',
            'start_date' => Carbon::now()->subDays(29),
            'end_date' => Carbon::now()->subDays(18),
            'status' => 'Готово',
            'order' => 3,
        ]);

        $task4_1 = $stage4->tasks()->create([
            'name' => 'Установка маяков',
            'description' => 'Выставить маяки по уровню во всех помещениях',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Завершена',
            'cost' => 6000.00,
            'order' => 0,
        ]);
        $task4_1->comments()->create([
            'user_id' => $employee3->id, 
            'comment' => 'Маяки выставлены, проверил лазерным уровнем',
            'created_at' => Carbon::now()->subDays(29)
        ]);

        $task4_2 = $stage4->tasks()->create([
            'name' => 'Оштукатуривание стен в комнатах',
            'description' => 'Нанести штукатурку Knauf Rotband',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Завершена',
            'cost' => 45000.00,
            'order' => 1,
        ]);
        $task4_2->comments()->createMany([
            ['user_id' => $employee3->id, 'comment' => 'Начал с гостиной', 'created_at' => Carbon::now()->subDays(27)],
            ['user_id' => $employee3->id, 'comment' => 'Закончил спальни, осталась кухня', 'created_at' => Carbon::now()->subDays(23)],
            ['user_id' => $employee3->id, 'comment' => 'Все комнаты оштукатурены', 'created_at' => Carbon::now()->subDays(20)],
            ['user_id' => $foreman->id, 'comment' => 'Проверил правилом - идеально ровно!', 'created_at' => Carbon::now()->subDays(19)],
        ]);

        $task4_3 = $stage4->tasks()->create([
            'name' => 'Оштукатуривание стен в санузлах',
            'description' => 'Нанести влагостойкую штукатурку',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Завершена',
            'cost' => 12000.00,
            'order' => 2,
        ]);

        // Материалы для штукатурки
        $stage4->materials()->createMany([
            [
                'user_id' => $foreman->id,
                'name' => 'Штукатурка Knauf Rotband',
                'description' => 'Для стен',
                'unit' => 'мешок 30кг',
                'quantity' => 45,
                'price_per_unit' => 420.00,
                'total_cost' => 18900.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Штукатурка влагостойкая Ceresit CR65',
                'description' => 'Для санузлов',
                'unit' => 'мешок 25кг',
                'quantity' => 12,
                'price_per_unit' => 580.00,
                'total_cost' => 6960.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Маяки штукатурные 6мм',
                'description' => '3м',
                'unit' => 'шт',
                'quantity' => 50,
                'price_per_unit' => 35.00,
                'total_cost' => 1750.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Грунтовка глубокого проникновения',
                'description' => 'Ceresit CT17',
                'unit' => 'л',
                'quantity' => 40,
                'price_per_unit' => 95.00,
                'total_cost' => 3800.00,
            ],
        ]);

        // ========================================================
        // ЭТАП 5: Стяжка пола (Завершен)
        // ========================================================
        $stage5 = $project->stages()->create([
            'name' => 'Стяжка пола',
            'start_date' => Carbon::now()->subDays(17),
            'end_date' => Carbon::now()->subDays(10),
            'status' => 'Готово',
            'order' => 4,
        ]);

        $task5_1 = $stage5->tasks()->create([
            'name' => 'Гидроизоляция основания',
            'description' => 'Уложить гидроизоляционную пленку',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Завершена',
            'cost' => 5000.00,
            'order' => 0,
        ]);

        $task5_2 = $stage5->tasks()->create([
            'name' => 'Установка маяков для стяжки',
            'description' => 'Выставить маяки по уровню',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Завершена',
            'cost' => 4000.00,
            'order' => 1,
        ]);

        $task5_3 = $stage5->tasks()->create([
            'name' => 'Заливка стяжки',
            'description' => 'Залить цементно-песчаную стяжку толщиной 50мм',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Завершена',
            'cost' => 38000.00,
            'order' => 2,
        ]);
        $task5_3->comments()->createMany([
            ['user_id' => $employee3->id, 'comment' => 'Залил стяжку, нужно дать высохнуть 7 дней', 'created_at' => Carbon::now()->subDays(14)],
            ['user_id' => $foreman->id, 'comment' => 'Хорошо, следите за влажностью', 'created_at' => Carbon::now()->subDays(14)],
            ['user_id' => $employee3->id, 'comment' => 'Накрыл пленкой, проветриваю', 'created_at' => Carbon::now()->subDays(13)],
        ]);

        // Материалы для стяжки
        $stage5->materials()->createMany([
            [
                'user_id' => $foreman->id,
                'name' => 'Цемент М500',
                'description' => 'Для стяжки',
                'unit' => 'мешок 50кг',
                'quantity' => 30,
                'price_per_unit' => 320.00,
                'total_cost' => 9600.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Песок речной',
                'description' => 'Мытый',
                'unit' => 'тонна',
                'quantity' => 3,
                'price_per_unit' => 1200.00,
                'total_cost' => 3600.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Пленка гидроизоляционная',
                'description' => '200 мкм',
                'unit' => 'м2',
                'quantity' => 80,
                'price_per_unit' => 45.00,
                'total_cost' => 3600.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Демпферная лента',
                'description' => 'По периметру',
                'unit' => 'м',
                'quantity' => 60,
                'price_per_unit' => 25.00,
                'total_cost' => 1500.00,
            ],
        ]);

        // ========================================================
        // ЭТАП 6: Укладка плитки в санузлах (В РАБОТЕ - ТЕКУЩИЙ)
        // ========================================================
        $stage6 = $project->stages()->create([
            'name' => 'Укладка плитки в санузлах',
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->addDays(3),
            'status' => 'В работе',
            'order' => 5,
        ]);

        $task6_1 = $stage6->tasks()->create([
            'name' => 'Подготовка поверхности стен',
            'description' => 'Грунтовка стен, проверка геометрии',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Завершена',
            'cost' => 4000.00,
            'order' => 0,
        ]);
        $task6_1->comments()->createMany([
            ['user_id' => $employee3->id, 'comment' => 'Стены загрунтованы в 2 слоя', 'created_at' => Carbon::now()->subDays(5)],
            ['user_id' => $foreman->id, 'comment' => 'Хорошо, можно начинать укладку', 'created_at' => Carbon::now()->subDays(5)],
        ]);

        $task6_2 = $stage6->tasks()->create([
            'name' => 'Укладка плитки на пол в ванной',
            'description' => 'Керамогранит 60x60см, цвет серый',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'В работе',
            'cost' => 18000.00,
            'order' => 1,
        ]);
        $task6_2->comments()->createMany([
            ['user_id' => $employee3->id, 'comment' => 'Начал укладку от дальнего угла', 'created_at' => Carbon::now()->subDays(4)],
            ['user_id' => $employee3->id, 'comment' => 'Сделано примерно 60%, плитка ложится отлично', 'created_at' => Carbon::now()->subDays(3)],
            ['user_id' => $foreman->id, 'comment' => 'Проверил швы - идеально ровно!', 'created_at' => Carbon::now()->subDays(2)],
            ['user_id' => $client->id, 'comment' => 'Очень нравится как получается!', 'created_at' => Carbon::now()->subDays(2)],
            ['user_id' => $employee3->id, 'comment' => 'Сегодня закончу пол, завтра начну стены', 'created_at' => Carbon::now()->subDays(1)],
        ]);

        $task6_3 = $stage6->tasks()->create([
            'name' => 'Укладка плитки на стены в ванной',
            'description' => 'Керамическая плитка 30x60см, белая глянцевая',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Не начата',
            'cost' => 25000.00,
            'order' => 2,
        ]);

        $task6_4 = $stage6->tasks()->create([
            'name' => 'Укладка плитки на пол в туалете',
            'description' => 'Керамогранит 60x60см, серый (как в ванной)',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Не начата',
            'cost' => 8000.00,
            'order' => 3,
        ]);

        $task6_5 = $stage6->tasks()->create([
            'name' => 'Укладка плитки на стены в туалете',
            'description' => 'Керамическая плитка 30x60см, белая глянцевая',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Не начата',
            'cost' => 12000.00,
            'order' => 4,
        ]);

        $task6_6 = $stage6->tasks()->create([
            'name' => 'Затирка швов',
            'description' => 'Затирка Ceresit CE40, цвет серебристо-серый',
            'created_by' => $foreman->id,
            'assigned_to' => $employee3->id,
            'status' => 'Не начата',
            'cost' => 6000.00,
            'order' => 5,
        ]);

        // Материалы для плитки
        $stage6->materials()->createMany([
            [
                'user_id' => $foreman->id,
                'name' => 'Керамогранит 60x60см серый',
                'description' => 'Для пола в санузлах',
                'unit' => 'м2',
                'quantity' => 12,
                'price_per_unit' => 890.00,
                'total_cost' => 10680.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Плитка керамическая 30x60см белая',
                'description' => 'Для стен санузлов',
                'unit' => 'м2',
                'quantity' => 35,
                'price_per_unit' => 650.00,
                'total_cost' => 22750.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Клей плиточный Ceresit CM11',
                'description' => 'Для внутренних работ',
                'unit' => 'мешок 25кг',
                'quantity' => 18,
                'price_per_unit' => 380.00,
                'total_cost' => 6840.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Затирка Ceresit CE40',
                'description' => 'Цвет серебристо-серый',
                'unit' => 'кг',
                'quantity' => 8,
                'price_per_unit' => 320.00,
                'total_cost' => 2560.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Крестики для плитки 2мм',
                'description' => 'Для ровных швов',
                'unit' => 'уп 200шт',
                'quantity' => 5,
                'price_per_unit' => 120.00,
                'total_cost' => 600.00,
            ],
        ]);

        // ========================================================
        // ЭТАП 7: Чистовая электрика (Не начат)
        // ========================================================
        $stage7 = $project->stages()->create([
            'name' => 'Чистовая электрика',
            'start_date' => Carbon::now()->addDays(4),
            'end_date' => Carbon::now()->addDays(8),
            'status' => 'Не начат',
            'order' => 6,
        ]);

        $task7_1 = $stage7->tasks()->create([
            'name' => 'Установка розеток и выключателей',
            'description' => 'Установить розетки Legrand Valena, выключатели',
            'created_by' => $foreman->id,
            'assigned_to' => $employee1->id,
            'status' => 'Не начата',
            'cost' => 15000.00,
            'order' => 0,
        ]);

        $task7_2 = $stage7->tasks()->create([
            'name' => 'Монтаж светильников',
            'description' => 'Установить потолочные и настенные светильники',
            'created_by' => $foreman->id,
            'assigned_to' => $employee1->id,
            'status' => 'Не начата',
            'cost' => 12000.00,
            'order' => 1,
        ]);

        $task7_3 = $stage7->tasks()->create([
            'name' => 'Сборка электрощита',
            'description' => 'Собрать щит, установить автоматы, УЗО, подключить группы',
            'created_by' => $foreman->id,
            'assigned_to' => $employee1->id,
            'status' => 'Не начата',
            'cost' => 18000.00,
            'order' => 2,
        ]);

        $task7_4 = $stage7->tasks()->create([
            'name' => 'Проверка и тестирование',
            'description' => 'Проверить все цепи, замерить сопротивление изоляции',
            'created_by' => $foreman->id,
            'assigned_to' => $employee1->id,
            'status' => 'Не начата',
            'cost' => 5000.00,
            'order' => 3,
        ]);

        // Материалы для чистовой электрики
        $stage7->materials()->createMany([
            [
                'user_id' => $foreman->id,
                'name' => 'Розетки Legrand Valena',
                'description' => 'С заземлением, белые',
                'unit' => 'шт',
                'quantity' => 35,
                'price_per_unit' => 420.00,
                'total_cost' => 14700.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Выключатели Legrand Valena',
                'description' => '1-клавишные и 2-клавишные',
                'unit' => 'шт',
                'quantity' => 15,
                'price_per_unit' => 380.00,
                'total_cost' => 5700.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Электрощит ABB',
                'description' => 'На 36 модулей, встраиваемый',
                'unit' => 'шт',
                'quantity' => 1,
                'price_per_unit' => 3500.00,
                'total_cost' => 3500.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Автоматы ABB',
                'description' => '16А, 25А, 40А',
                'unit' => 'шт',
                'quantity' => 12,
                'price_per_unit' => 450.00,
                'total_cost' => 5400.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'УЗО ABB',
                'description' => '40А, 30мА',
                'unit' => 'шт',
                'quantity' => 3,
                'price_per_unit' => 2800.00,
                'total_cost' => 8400.00,
            ],
        ]);

        // ========================================================
        // ЭТАП 8: Покраска стен (Не начат)
        // ========================================================
        $stage8 = $project->stages()->create([
            'name' => 'Покраска стен и поклейка обоев',
            'start_date' => Carbon::now()->addDays(9),
            'end_date' => Carbon::now()->addDays(15),
            'status' => 'Не начат',
            'order' => 7,
        ]);

        $task8_1 = $stage8->tasks()->create([
            'name' => 'Шпаклевка стен под покраску',
            'description' => 'Нанести финишную шпаклевку Knauf Polymer Finish',
            'created_by' => $foreman->id,
            'status' => 'Не начата',
            'cost' => 35000.00,
            'order' => 0,
        ]);

        $task8_2 = $stage8->tasks()->create([
            'name' => 'Шлифовка стен',
            'description' => 'Отшлифовать стены под покраску',
            'created_by' => $foreman->id,
            'status' => 'Не начата',
            'cost' => 12000.00,
            'order' => 1,
        ]);

        $task8_3 = $stage8->tasks()->create([
            'name' => 'Покраска стен в гостиной и спальнях',
            'description' => 'Краска Dulux, цвет светло-бежевый',
            'created_by' => $foreman->id,
            'status' => 'Не начата',
            'cost' => 28000.00,
            'order' => 2,
        ]);

        $task8_4 = $stage8->tasks()->create([
            'name' => 'Поклейка обоев на кухне',
            'description' => 'Виниловые обои под покраску',
            'created_by' => $foreman->id,
            'status' => 'Не начата',
            'cost' => 15000.00,
            'order' => 3,
        ]);

        // Материалы для покраски
        $stage8->materials()->createMany([
            [
                'user_id' => $foreman->id,
                'name' => 'Шпаклевка Knauf Polymer Finish',
                'description' => 'Финишная, под покраску',
                'unit' => 'мешок 20кг',
                'quantity' => 25,
                'price_per_unit' => 680.00,
                'total_cost' => 17000.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Краска Dulux',
                'description' => 'Моющаяся, светло-бежевая',
                'unit' => 'л',
                'quantity' => 60,
                'price_per_unit' => 520.00,
                'total_cost' => 31200.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Обои виниловые',
                'description' => 'Под покраску, ширина 1.06м',
                'unit' => 'рулон',
                'quantity' => 12,
                'price_per_unit' => 890.00,
                'total_cost' => 10680.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Грунтовка под покраску',
                'description' => 'Dulux',
                'unit' => 'л',
                'quantity' => 30,
                'price_per_unit' => 280.00,
                'total_cost' => 8400.00,
            ],
        ]);

        // ========================================================
        // ЭТАП 9: Установка дверей (Не начат)
        // ========================================================
        $stage9 = $project->stages()->create([
            'name' => 'Установка дверей',
            'start_date' => Carbon::now()->addDays(16),
            'end_date' => Carbon::now()->addDays(19),
            'status' => 'Не начат',
            'order' => 8,
        ]);

        $task9_1 = $stage9->tasks()->create([
            'name' => 'Установка входной двери',
            'description' => 'Металлическая дверь с терморазрывом',
            'created_by' => $foreman->id,
            'status' => 'Не начата',
            'cost' => 15000.00,
            'order' => 0,
        ]);

        $task9_2 = $stage9->tasks()->create([
            'name' => 'Установка межкомнатных дверей',
            'description' => 'Двери экошпон, цвет беленый дуб, 5 шт',
            'created_by' => $foreman->id,
            'status' => 'Не начата',
            'cost' => 25000.00,
            'order' => 1,
        ]);

        // Материалы для дверей
        $stage9->materials()->createMany([
            [
                'user_id' => $foreman->id,
                'name' => 'Дверь входная металлическая',
                'description' => 'С терморазрывом, замок класса 3',
                'unit' => 'шт',
                'quantity' => 1,
                'price_per_unit' => 35000.00,
                'total_cost' => 35000.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Двери межкомнатные экошпон',
                'description' => 'Беленый дуб, 800x2000мм',
                'unit' => 'шт',
                'quantity' => 5,
                'price_per_unit' => 8500.00,
                'total_cost' => 42500.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Доборы и наличники',
                'description' => 'Комплекты к дверям',
                'unit' => 'компл',
                'quantity' => 5,
                'price_per_unit' => 2200.00,
                'total_cost' => 11000.00,
            ],
        ]);

        // ========================================================
        // ЭТАП 10: Укладка ламината (Не начат)
        // ========================================================
        $stage10 = $project->stages()->create([
            'name' => 'Укладка ламината',
            'start_date' => Carbon::now()->addDays(20),
            'end_date' => Carbon::now()->addDays(24),
            'status' => 'Не начат',
            'order' => 9,
        ]);

        $task10_1 = $stage10->tasks()->create([
            'name' => 'Укладка подложки',
            'description' => 'Рулонная подложка 3мм',
            'created_by' => $foreman->id,
            'status' => 'Не начата',
            'cost' => 4000.00,
            'order' => 0,
        ]);

        $task10_2 = $stage10->tasks()->create([
            'name' => 'Укладка ламината',
            'description' => 'Ламинат 33 класс, дуб натуральный, 65 м2',
            'created_by' => $foreman->id,
            'status' => 'Не начата',
            'cost' => 32000.00,
            'order' => 1,
        ]);

        $task10_3 = $stage10->tasks()->create([
            'name' => 'Установка плинтусов',
            'description' => 'Плинтус МДФ с кабель-каналом',
            'created_by' => $foreman->id,
            'status' => 'Не начата',
            'cost' => 8000.00,
            'order' => 2,
        ]);

        // Материалы для ламината
        $stage10->materials()->createMany([
            [
                'user_id' => $foreman->id,
                'name' => 'Ламинат 33 класс Quick-Step',
                'description' => 'Дуб натуральный',
                'unit' => 'м2',
                'quantity' => 70,
                'price_per_unit' => 980.00,
                'total_cost' => 68600.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Подложка под ламинат',
                'description' => 'Рулонная, 3мм',
                'unit' => 'м2',
                'quantity' => 70,
                'price_per_unit' => 65.00,
                'total_cost' => 4550.00,
            ],
            [
                'user_id' => $foreman->id,
                'name' => 'Плинтус МДФ',
                'description' => 'С кабель-каналом, дуб',
                'unit' => 'м',
                'quantity' => 60,
                'price_per_unit' => 180.00,
                'total_cost' => 10800.00,
            ],
        ]);

        $this->command->info('✅ Создан детально заполненный проект "Капитальный ремонт 3-комнатной квартиры"!');
        $this->command->info('📊 Статистика проекта:');
        $this->command->info('   - 10 этапов работ');
        $this->command->info('   - 40+ задач с комментариями');
        $this->command->info('   - 5 участников проекта');
        $this->command->info('   - Материалы на всех этапах');
        $this->command->info('   - Активные комментарии от команды и клиента');
        $this->command->info('');
        $this->command->info('📧 Учетные данные для входа:');
        $this->command->info('   Прораб: foreman@example.com / password');
        $this->command->info('   Клиент: client@example.com / password');
        $this->command->info('   Сотрудник 1: employee1@example.com / password');
        $this->command->info('   Сотрудник 2: employee2@example.com / password');
        $this->command->info('   Сотрудник 3: employee3@example.com / password');
    }
}
            