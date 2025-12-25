@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (Auth::user()->isClient())
                {{-- Страница для клиентов --}}
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-rocket-takeoff" style="font-size: 4rem; color: #28a745;"></i>
                    </div>
                    <h2>Станьте прорабом!</h2>
                    <p class="text-muted mb-4">Начните управлять своими строительными проектами</p>
                    
                    <div class="card shadow-sm mx-auto" style="max-width: 500px;">
                        <div class="card-body p-4">
                            <h5 class="mb-3">🎁 Стартовый тариф "Прораб Старт"</h5>
                            <ul class="text-start mb-4">
                                <li>✅ До 2 проектов</li>
                                <li>✅ Управление этапами и задачами</li>
                                <li>✅ Добавление участников</li>
                                <li>✅ Загрузка документов</li>
                            </ul>
                            <a href="{{ route('pricing.index') }}" class="btn btn-success btn-lg w-100">
                                <i class="bi bi-star"></i> Активировать бесплатный тариф
                            </a>
                        </div>
                    </div>
                </div>
            @else
                {{-- Перенаправление на список проектов для прорабов --}}
                <script>
                    window.location.href = "{{ route('projects.index') }}";
                </script>
            @endif
        </div>
    </div>
</div>
@endsection
