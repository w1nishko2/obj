<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        
        // Логирование ошибок валидации
        $this->renderable(function (ValidationException $e, $request) {
            Log::warning('Ошибка валидации', [
                'url' => $request->fullUrl(),
                'errors' => $e->errors(),
                'input' => $request->except(['password', 'password_confirmation', '_token'])
            ]);
        });
    }
}
