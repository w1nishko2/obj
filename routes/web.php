<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Публичный лендинг - главная страница
Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');
Route::post('/contact', [App\Http\Controllers\LandingController::class, 'contact'])->name('landing.contact');

// SEO файлы
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Публичная страница для инвесторов
Route::get('/investors', function () {
    return view('investors');
})->name('investors');

// Тестовая страница для проверки CSRF Token Auto-Refresh
Route::get('/csrf-test', function () {
    return view('csrf-test');
})->name('csrf.test');

// Альтернативный URL для лендинга
Route::get('/landing', function () {
    return redirect()->route('landing', [], 301);
});

Auth::routes();

if (app()->environment('production')) {
    URL::forceScheme('https');
}

// Публичные документы (доступны без авторизации)
Route::get('/privacy-policy', function () {
    return view('documents.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('documents.terms-of-service');
})->name('terms-of-service');

Route::get('/requisites', function () {
    return view('documents.requisites');
})->name('requisites');

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\ProjectController::class, 'index'])->name('home');
    
    // Выбор роли пользователя
    Route::post('/user/select-role', [App\Http\Controllers\ProfileController::class, 'selectRole'])->name('user.select-role');
    
    // Прайсы и шаблоны работ
    Route::get('/prices', [App\Http\Controllers\PriceTemplateController::class, 'index'])->name('prices.index');
    Route::get('/prices/templates', [App\Http\Controllers\PriceTemplateController::class, 'getTemplates'])->name('prices.templates');
    Route::post('/prices/types', [App\Http\Controllers\PriceTemplateController::class, 'storeType'])->name('prices.types.store');
    Route::put('/prices/types/{type}', [App\Http\Controllers\PriceTemplateController::class, 'updateType'])->name('prices.types.update');
    Route::delete('/prices/types/{type}', [App\Http\Controllers\PriceTemplateController::class, 'deleteType'])->name('prices.types.delete');
    Route::post('/prices/stages', [App\Http\Controllers\PriceTemplateController::class, 'storeStage'])->name('prices.stages.store');
    Route::put('/prices/stages/{stage}', [App\Http\Controllers\PriceTemplateController::class, 'updateStage'])->name('prices.stages.update');
    Route::delete('/prices/stages/{stage}', [App\Http\Controllers\PriceTemplateController::class, 'deleteStage'])->name('prices.stages.delete');
    Route::post('/prices/tasks', [App\Http\Controllers\PriceTemplateController::class, 'storeTask'])->name('prices.tasks.store');
    Route::put('/prices/tasks/{task}', [App\Http\Controllers\PriceTemplateController::class, 'updateTask'])->name('prices.tasks.update');
    Route::delete('/prices/tasks/{task}', [App\Http\Controllers\PriceTemplateController::class, 'deleteTask'])->name('prices.tasks.delete');
    
    // Проекты
    Route::resource('projects', App\Http\Controllers\ProjectController::class);
    Route::get('/projects-archived', [App\Http\Controllers\ProjectController::class, 'archived'])->name('projects.archived');
    Route::post('/projects/{project}/archive', [App\Http\Controllers\ProjectController::class, 'archive'])->name('projects.archive');
    Route::post('/projects/{project}/unarchive', [App\Http\Controllers\ProjectController::class, 'unarchive'])->name('projects.unarchive');
    Route::post('/projects/{project}/stages', [App\Http\Controllers\ProjectController::class, 'storeStage'])->name('projects.stages.store');
    Route::put('/projects/{project}/stages/{stage}', [App\Http\Controllers\ProjectController::class, 'updateStage'])->name('projects.stages.update');
    Route::post('/projects/{project}/stages/{stage}/status', [App\Http\Controllers\ProjectController::class, 'updateStageStatus'])->name('projects.stages.status');
    Route::delete('/projects/{project}/stages/{stage}', [App\Http\Controllers\ProjectController::class, 'destroyStage'])->name('projects.stages.destroy');
    Route::post('/projects/{project}/participants', [App\Http\Controllers\ProjectController::class, 'addParticipant'])->name('projects.participants.add');
    Route::delete('/projects/{project}/participants/{participant}', [App\Http\Controllers\ProjectController::class, 'removeParticipant'])->name('projects.participants.remove');
    Route::post('/projects/{project}/documents', [App\Http\Controllers\ProjectController::class, 'uploadDocument'])->name('projects.documents.upload');
    Route::delete('/projects/{project}/documents/{document}', [App\Http\Controllers\ProjectController::class, 'deleteDocument'])->name('projects.documents.delete');
    
    // Сметы проекта
    Route::get('/projects/{project}/estimate/pdf', [App\Http\Controllers\ProjectController::class, 'generateEstimatePDF'])->name('projects.estimate.pdf');
    Route::get('/projects/{project}/estimate/excel', [App\Http\Controllers\ProjectController::class, 'generateEstimateExcel'])->name('projects.estimate.excel');
    
    // Шаблоны документов и данные клиента
    Route::get('/projects/{project}/documents/templates', [App\Http\Controllers\DocumentTemplateController::class, 'index'])->name('projects.documents.templates');
    Route::get('/projects/{project}/documents/templates/{template}/generate', [App\Http\Controllers\DocumentTemplateController::class, 'generate'])->name('projects.documents.generate');
    Route::get('/projects/{project}/client-data/edit', [App\Http\Controllers\DocumentTemplateController::class, 'editClientData'])->name('projects.client-data.edit');
    Route::put('/projects/{project}/client-data', [App\Http\Controllers\DocumentTemplateController::class, 'updateClientData'])->name('projects.client-data.update');
    
    // Поиск и пагинация (Ajax) - ДОЛЖНЫ БЫТЬ ПЕРЕД динамическими маршрутами!
    Route::get('/projects/{project}/search-stages', [App\Http\Controllers\ProjectController::class, 'searchStages'])->name('projects.stages.search');
    
    // Этапы и задачи
    Route::get('/projects/{project}/stages/{stage}', [App\Http\Controllers\StageTaskController::class, 'showStage'])->name('stages.show');
    Route::post('/projects/{project}/stages/{stage}/tasks', [App\Http\Controllers\StageTaskController::class, 'storeTask'])->name('stages.tasks.store');
    Route::put('/projects/{project}/stages/{stage}/tasks/{task}', [App\Http\Controllers\StageTaskController::class, 'updateTask'])->name('stages.tasks.update');
    Route::post('/projects/{project}/stages/{stage}/tasks/{task}/status', [App\Http\Controllers\StageTaskController::class, 'updateTaskStatus'])->name('stages.tasks.status');
    Route::delete('/projects/{project}/stages/{stage}/tasks/{task}', [App\Http\Controllers\StageTaskController::class, 'destroyTask'])->name('stages.tasks.destroy');
    
    // Поиск задач (работает, т.к. идет после {stage})
    Route::get('/projects/{project}/stages/{stage}/tasks/search', [App\Http\Controllers\StageTaskController::class, 'searchTasks'])->name('stages.tasks.search');
    
    // Комментарии и фото к задачам
    Route::post('/projects/{project}/stages/{stage}/tasks/{task}/comments', [App\Http\Controllers\StageTaskController::class, 'addComment'])->name('stages.tasks.comments.add');
    Route::delete('/projects/{project}/stages/{stage}/tasks/{task}/comments/{comment}', [App\Http\Controllers\StageTaskController::class, 'destroyComment'])->name('stages.tasks.comments.destroy');
    Route::post('/projects/{project}/stages/{stage}/tasks/{task}/photos', [App\Http\Controllers\StageTaskController::class, 'addPhoto'])->name('stages.tasks.photos.add');
    Route::delete('/projects/{project}/stages/{stage}/tasks/{task}/photos/{photo}', [App\Http\Controllers\StageTaskController::class, 'destroyPhoto'])->name('stages.tasks.photos.destroy');
    
    // Материалы этапа
    Route::post('/projects/{project}/stages/{stage}/materials', [App\Http\Controllers\StageTaskController::class, 'storeMaterial'])->name('stages.materials.store');
    Route::delete('/projects/{project}/stages/{stage}/materials/{material}', [App\Http\Controllers\StageTaskController::class, 'destroyMaterial'])->name('stages.materials.destroy');
    
    // Доставки этапа
    Route::post('/projects/{project}/stages/{stage}/deliveries', [App\Http\Controllers\StageTaskController::class, 'storeDelivery'])->name('stages.deliveries.store');
    Route::delete('/projects/{project}/stages/{stage}/deliveries/{delivery}', [App\Http\Controllers\StageTaskController::class, 'destroyDelivery'])->name('stages.deliveries.destroy');
    
    // Профиль пользователя
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile/name', [App\Http\Controllers\ProfileController::class, 'updateName'])->name('profile.update.name');
    Route::patch('/profile/foreman-data', [App\Http\Controllers\ProfileController::class, 'updateForemanData'])->name('profile.update.foreman-data');
    Route::post('/profile/send-verification-code', [App\Http\Controllers\ProfileController::class, 'sendEmailVerificationCode'])->name('profile.send-verification-code');
    Route::post('/profile/verify-code', [App\Http\Controllers\ProfileController::class, 'verifyEmailCode'])->name('profile.verify-code');
    Route::patch('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'deleteAccount'])->name('profile.delete');
    
    // Тарифы и подписка
    Route::get('/pricing', function () {
        $plans = \App\Models\Plan::where('is_active', true)->orderBy('price')->get();
        return view('pricing.new-index', compact('plans'));
    })->name('pricing.index');
    

    
    // Платежи
    Route::post('/payment/create', [App\Http\Controllers\PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/success/{payment}', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/history', [App\Http\Controllers\PaymentController::class, 'history'])->name('payment.history');
    Route::get('/payment/webhook-setup', function () {
        return view('payment.webhook-setup');
    })->name('payment.webhook-setup');
    
    // Push-уведомления - управление подписками (для авторизованных)
    Route::post('/push/subscribe', [App\Http\Controllers\PushSubscriptionController::class, 'subscribe'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [App\Http\Controllers\PushSubscriptionController::class, 'unsubscribe'])->name('push.unsubscribe');
    Route::get('/push/subscriptions', [App\Http\Controllers\PushSubscriptionController::class, 'getSubscriptions'])->name('push.subscriptions');
    
    // Push-уведомления - тестовая страница
    Route::get('/push-test', function () {
        return view('push-test');
    })->name('push.test');
    
    // Push-уведомления - диагностика
    Route::get('/push-debug', function () {
        return view('push-debug');
    })->name('push.debug');
    
    // Отправка тестового push (публичный для тестирования)
    Route::post('/push/send-test-push', [App\Http\Controllers\PushTestController::class, 'sendTestPush'])->name('push.send-test-push');
    
    // Отправка push-уведомлений (для авторизованных)
    Route::post('/push/send-test', [App\Http\Controllers\PushNotificationController::class, 'sendTestNotification'])->name('push.send-test');
    Route::post('/push/send', [App\Http\Controllers\PushNotificationController::class, 'sendCustomNotification'])->name('push.send');
    Route::post('/push/send-all', [App\Http\Controllers\PushNotificationController::class, 'sendToAll'])->name('push.send-all');
});

// Админ-панель (только для администраторов)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('index');
    Route::get('/clients', [App\Http\Controllers\AdminController::class, 'clients'])->name('clients');
    Route::post('/users/{userId}/role', [App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('users.update-role');
    Route::post('/users/{userId}/subscription', [App\Http\Controllers\AdminController::class, 'updateUserSubscription'])->name('users.update-subscription');
    
    // Промокоды
    Route::get('/promocodes', [App\Http\Controllers\AdminController::class, 'promocodes'])->name('promocodes');
    Route::post('/promocodes', [App\Http\Controllers\AdminController::class, 'createPromocode'])->name('promocodes.create');
    Route::put('/promocodes/{id}', [App\Http\Controllers\AdminController::class, 'updatePromocode'])->name('promocodes.update');
    Route::delete('/promocodes/{id}', [App\Http\Controllers\AdminController::class, 'deletePromocode'])->name('promocodes.delete');
    Route::get('/promocodes/{id}/analytics', [App\Http\Controllers\AdminController::class, 'promocodeAnalytics'])->name('promocodes.analytics');
});

// API для проверки промокодов (доступно авторизованным пользователям)
Route::middleware(['auth'])->post('/api/promocode/validate', [App\Http\Controllers\AdminController::class, 'validatePromocode'])->name('api.promocode.validate');

// Публичные push-маршруты (без авторизации)
Route::get('/push/vapid-public-key', [App\Http\Controllers\PushSubscriptionController::class, 'getVapidPublicKey'])->name('push.vapid-key');

// CSRF Token Refresh (без CSRF проверки для обновления токена)
Route::get('/refresh-csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
})->name('refresh-csrf');

// Webhook от YooKassa (без авторизации)
Route::post('/payment/webhook', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');
