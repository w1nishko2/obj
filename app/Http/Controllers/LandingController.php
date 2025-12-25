<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Показать главную лендинговую страницу
     */
    public function index()
    {
        // Если пользователь авторизован, перенаправляем на home
        if (auth()->check()) {
            return redirect()->route('home');
        }
        
        // Статические тарифы для лендинга
        $plans = collect([
            (object) [
                'name' => 'Прораб Старт',
                'slug' => 'start',
                'description' => 'Бесплатный тариф для начала работы',
                'price' => 50,
                'features' => [
                    'До 2 проектов одновременно',
                    'Базовое управление задачами',
                    'Создание простых смет',
                    'Загрузка до 50 фото',
                    'Базовый клиентский доступ',
                    'Email поддержка',
                    'Мобильное приложение',
                    'Облачное хранилище 1 ГБ'
                ],
                'isFree' => function() { return false; }
            ],
            (object) [
                'name' => 'Месячная подписка',
                'slug' => 'monthly',
                'description' => 'Статус Прораба на 1 месяц',
                'price' => 2000,
                'features' => [
                    'Неограниченное количество проектов',
                    'Управление командой до 10 человек',
                    'Автоматические сметы и документы',
                    'Неограниченное количество фото',
                    'Расширенный клиентский портал',
                    'Аналитика и отчеты в реальном времени',
                    'Экспорт в Excel/PDF/Word',
                    'Приоритетная поддержка 24/7',
                    'Облачное хранилище 50 ГБ'
                ],
                'isFree' => function() { return false; }
            ],
            (object) [
                'name' => 'Годовая подписка',
                'slug' => 'yearly',
                'description' => 'Статус Прораба на 12 месяцев с выгодой 25%',
                'price' => 18000,
                'features' => [
                    'Все возможности месячной подписки',
                    'Неограниченное количество проектов и команды',
                    'Расширенная аналитика и прогнозы',
                    'Интеграция с 1C и другими системами',
                    'API доступ для автоматизации',
                    'Персональный менеджер',
                    'Индивидуальное обучение команды',
                    'Приоритетное развитие функций',
                    'Облачное хранилище 200 ГБ',
                    'Экономия 6 000 ₽ в год'
                ],
                'isFree' => function() { return false; }
            ]
        ]);

        return view('landing.index', compact('plans'));
    }

    /**
     * Обработка формы обратной связи
     */
    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        // Здесь можно добавить отправку email или сохранение в БД
        // Например: Mail::to('admin@example.com')->send(new ContactForm($validated));

        return back()->with('success', 'Спасибо за обращение! Мы свяжемся с вами в ближайшее время.');
    }
}
