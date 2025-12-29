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
        
        // Получаем актуальные месячные тарифы из базы данных
        $plans = collect([
            (object) [
                'name' => 'Стартовый',
                'slug' => 'starter',
                'description' => 'Для прорабов-одиночек (1-3 объекта)',
                'price' => 490,
                'period' => 'месяц',
                'yearly_price' => 4900,
                'yearly_slug' => 'starter_yearly',
                'features' => [
                    'До 3 активных проектов',
                    'До 10 участников на проект',
                    'Неограниченные этапы и задачи',
                    'Комментарии и фото к задачам',
                    'Учет материалов и доставок',
                    'Push-уведомления',
                    'Генерация смет (PDF/Excel)',
                    'Генерация договоров и актов'
                ],
            ],
            (object) [
                'name' => 'Профессиональный',
                'slug' => 'professional',
                'description' => 'Для опытных прорабов (4-10 объектов)',
                'price' => 1290,
                'period' => 'месяц',
                'yearly_price' => 12900,
                'yearly_slug' => 'professional_yearly',
                'popular' => true,
                'features' => [
                    'До 10 активных проектов',
                    'До 30 участников на проект',
                    'Все из Стартового тарифа',
                    'Расширенные шаблоны документов',
                    'Вечный архив проектов',
                    'Генерация актов выполненных работ',
                    'Расширенная аналитика',
                    'Приоритетная поддержка'
                ],
            ],
            (object) [
                'name' => 'Корпоративный',
                'slug' => 'corporate',
                'description' => 'Для компаний (10+ объектов)',
                'price' => 2990,
                'period' => 'месяц',
                'yearly_price' => 29900,
                'yearly_slug' => 'corporate_yearly',
                'features' => [
                    'Неограниченное количество проектов',
                    'Неограниченное количество участников',
                    'Все из Профессионального',
                    'Несколько прорабов/менеджеров',
                    'Персональный менеджер',
                    'Поддержка 24/7 (телефон)',
                    'Обучение команды',
                    'Кастомные доработки'
                ],
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
