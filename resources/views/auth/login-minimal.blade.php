<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Вход - {{ config('app.name', 'Laravel') }}</title>
    
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="auth-minimal-container">
        <div class="auth-minimal-card">
            <div class="auth-logo">
                <i class="bi bi-building"></i>
                <h1>{{ config('app.name', 'Laravel') }}</h1>
            </div>

            <div class="auth-header">
                <h2>Вход в систему</h2>
                <p>Введите email и пароль для продолжения</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group-minimal">
                    <label for="email">Email</label>
                    <input id="email" 
                           type="email" 
                           class="minimal-input @error('email') is-invalid @enderror" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="your@email.com"
                           required 
                           autocomplete="email" 
                           autofocus>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-minimal">
                    <label for="password">Пароль</label>
                    <input id="password" 
                           type="password" 
                           class="minimal-input @error('password') is-invalid @enderror" 
                           name="password" 
                           placeholder="••••••••"
                           required 
                           autocomplete="current-password">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-options">
                    <label class="checkbox-minimal">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Запомнить меня</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="auth-link">
                            Забыли пароль?
                        </a>
                    @endif
                </div>

                <button type="submit" class="minimal-btn minimal-btn-primary minimal-btn-large" style="width: 100%;">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Войти
                </button>

                @if (Route::has('register'))
                    <div class="auth-footer">
                        <span>Нет аккаунта?</span>
                        <a href="{{ route('register') }}" class="auth-link-primary">
                            Зарегистрироваться
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <div class="auth-background">
            <div class="auth-bg-gradient"></div>
        </div>
    </div>

    <style>
        /* Дополнительные стили для страницы входа */
        .auth-minimal-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .auth-background {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .auth-bg-gradient {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
        }

        .auth-minimal-card {
            background: white;
            border-radius: 16px;
            padding: 3rem;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .auth-logo h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            color: var(--text-secondary);
            margin: 0;
        }

        .auth-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .checkbox-minimal {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-minimal input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .checkbox-minimal span {
            font-size: 0.9375rem;
            color: var(--text-secondary);
        }

        .auth-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9375rem;
            transition: all 0.2s;
        }

        .auth-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
        }

        .auth-footer span {
            color: var(--text-secondary);
            margin-right: 0.5rem;
        }

        .auth-link-primary {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .auth-link-primary:hover {
            color: var(--primary-hover);
        }

        @media (max-width: 640px) {
            .auth-minimal-card {
                padding: 2rem 1.5rem;
            }

            .auth-options {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>
</body>
</html>
