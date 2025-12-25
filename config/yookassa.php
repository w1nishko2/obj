<?php

return [
    /*
    |--------------------------------------------------------------------------
    | YooKassa Configuration
    |--------------------------------------------------------------------------
    |
    | Настройки для интеграции с платежной системой ЮKassa (Яндекс.Касса)
    |
    */

    'shop_id' => env('YOOKASSA_SHOP_ID', '1231627'),
    'secret_key' => env('YOOKASSA_SECRET_KEY', ''),
    
    // URL для возврата после оплаты
    'return_url' => env('YOOKASSA_RETURN_URL', env('APP_URL') . '/payment/success'),
    
    // Включить тестовый режим
    'test_mode' => env('YOOKASSA_TEST_MODE', false),
];
