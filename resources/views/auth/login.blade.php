@extends('layouts.app')

@section('content')
<div class="minimal-container" style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 120px);">
    <div class="wizard-container" style="width: 100%; max-width: 500px;">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="wizard-content active">
                <div class="wizard-header">
                    <h2>Вход в систему</h2>
                    <p>Введите свои данные для входа</p>
                </div>

                <div class="form-group-minimal">
                    <label>Номер телефона</label>
                    <input id="phone" 
                           type="tel" 
                           class="minimal-input phone-mask @error('phone') is-invalid @enderror" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           placeholder="+7 (999) 123-45-67"
                           required 
                           autocomplete="tel" 
                           autofocus>
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-minimal">
                    <label>Пароль</label>
                    <input id="password" 
                           type="password" 
                           class="minimal-input @error('password') is-invalid @enderror" 
                           name="password" 
                           placeholder="Введите пароль"
                           required 
                           autocomplete="current-password">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-minimal">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="remember" 
                               id="remember" 
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Запомнить меня
                        </label>
                    </div>
                </div>
            </div>

            <div class="wizard-actions">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="minimal-btn minimal-btn-ghost">
                        Регистрация
                    </a>
                @endif
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="minimal-btn minimal-btn-ghost">
                        Забыли пароль?
                    </a>
                @endif
                <button type="submit" class="minimal-btn minimal-btn-primary">
                    Войти
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
