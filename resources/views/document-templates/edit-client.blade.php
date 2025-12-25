@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px;">
    <div class="row mb-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-compact mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Проекты</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('projects.show', $project) }}">{{ Str::limit($project->name, 30) }}</a></li>
                    <li class="breadcrumb-item active">Данные клиента</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="text-center mb-4">
        <h2><i class="bi bi-person-badge"></i> Данные клиента</h2>
        <p class="text-muted">Эти данные будут автоматически подставляться во все генерируемые документы</p>
    </div>

    <form action="{{ route('projects.client-data.update', $project) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Основная информация клиента -->
        <div class="minimal-card mb-3">
            <div class="minimal-card-header">
                <span><i class="bi bi-person"></i> Основная информация клиента</span>
            </div>
            <div class="minimal-card-body">
                <div class="form-group-minimal">
                    <label>ФИО клиента <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="minimal-input @error('client_full_name') is-invalid @enderror" 
                           name="client_full_name" 
                           value="{{ old('client_full_name', $project->client_full_name) }}"
                           placeholder="Иванов Иван Иванович"
                           maxlength="255"
                           required>
                    @error('client_full_name')
                        <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                    @enderror
                    <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">Пример: Иванов Иван Иванович</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Телефон</label>
                            <input type="text" 
                                   class="minimal-input @error('client_phone') is-invalid @enderror" 
                                   name="client_phone" 
                                   value="{{ old('client_phone', $project->client_phone) }}"
                                   placeholder="+7 (999) 123-45-67">
                            @error('client_phone')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">Формат: +7 (XXX) XXX-XX-XX</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Email</label>
                            <input type="email" 
                                   class="minimal-input @error('client_email') is-invalid @enderror" 
                                   name="client_email" 
                                   value="{{ old('client_email', $project->client_email) }}"
                                   placeholder="client@example.com"
                                   maxlength="255">
                            @error('client_email')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group-minimal">
                    <label>Адрес проживания клиента</label>
                    <textarea class="minimal-input @error('client_address') is-invalid @enderror" 
                              name="client_address" 
                              rows="2"
                              style="resize: vertical;"
                              placeholder="г. Москва, ул. Примерная, д. 1, кв. 1">{{ old('client_address', $project->client_address) }}</textarea>
                    @error('client_address')
                        <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Паспортные данные клиента -->
        <div class="minimal-card mb-3">
            <div class="minimal-card-header">
                <span><i class="bi bi-card-text"></i> Паспортные данные клиента</span>
            </div>
            <div class="minimal-card-body">
                <p class="text-muted small mb-3">Необходимы для договоров и актов</p>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group-minimal">
                            <label>Серия</label>
                            <input type="text" 
                                   class="minimal-input @error('client_passport_series') is-invalid @enderror" 
                                   name="client_passport_series" 
                                   value="{{ old('client_passport_series', $project->client_passport_series) }}"
                                   placeholder="1234"
                                   pattern="[0-9]{4}"
                                   maxlength="4">
                            @error('client_passport_series')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">4 цифры</small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group-minimal">
                            <label>Номер</label>
                            <input type="text" 
                                   class="minimal-input @error('client_passport_number') is-invalid @enderror" 
                                   name="client_passport_number" 
                                   value="{{ old('client_passport_number', $project->client_passport_number) }}"
                                   placeholder="567890"
                                   pattern="[0-9]{6}"
                                   maxlength="6">
                            @error('client_passport_number')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">6 цифр</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Дата выдачи</label>
                            <input type="text" 
                                   class="minimal-input flatpickr-single @error('client_passport_issued_date') is-invalid @enderror" 
                                   id="client_passport_issued_date" 
                                   name="client_passport_issued_date" 
                                   value="{{ old('client_passport_issued_date', $project->client_passport_issued_date ? $project->client_passport_issued_date->format('d.m.Y') : '') }}"
                                   placeholder="Выберите дату">
                            @error('client_passport_issued_date')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group-minimal">
                    <label>Кем выдан</label>
                    <input type="text" 
                           class="minimal-input @error('client_passport_issued_by') is-invalid @enderror" 
                           name="client_passport_issued_by" 
                           value="{{ old('client_passport_issued_by', $project->client_passport_issued_by) }}"
                           placeholder="Отделом УФМС России по г. Москва"
                           maxlength="255">
                    @error('client_passport_issued_by')
                        <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Юридические данные клиента -->
        <div class="minimal-card mb-3">
            <div class="minimal-card-header">
                <span><i class="bi bi-building"></i> Для юридических лиц (необязательно)</span>
            </div>
            <div class="minimal-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Название организации</label>
                            <input type="text" 
                                   class="minimal-input @error('client_organization_name') is-invalid @enderror" 
                                   name="client_organization_name" 
                                   value="{{ old('client_organization_name', $project->client_organization_name) }}"
                                   placeholder='ООО "Пример"'
                                   maxlength="255">
                            @error('client_organization_name')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>ИНН</label>
                            <input type="text" 
                                   class="minimal-input @error('client_inn') is-invalid @enderror" 
                                   name="client_inn" 
                                   value="{{ old('client_inn', $project->client_inn) }}"
                                   placeholder="1234567890 или 123456789012"
                                   pattern="[0-9]{10}|[0-9]{12}"
                                   maxlength="12">
                            @error('client_inn')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">10 цифр (юр. лицо) или 12 цифр (физ. лицо)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Основная информация прораба -->
        <div class="minimal-card mb-3">
            <div class="minimal-card-header">
                <span><i class="bi bi-person-workspace"></i> Основная информация прораба</span>
            </div>
            <div class="minimal-card-body">
                <p class="text-muted small mb-3">Эти данные также будут использоваться в документах</p>

                <div class="form-group-minimal">
                    <label>ФИО прораба <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="minimal-input @error('foreman_full_name') is-invalid @enderror" 
                           name="foreman_full_name" 
                           value="{{ old('foreman_full_name', $project->foreman_full_name) }}"
                           placeholder="Петров Петр Петрович"
                           maxlength="255"
                           required>
                    @error('foreman_full_name')
                        <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                    @enderror
                    <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">Пример: Петров Петр Петрович</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Телефон прораба</label>
                            <input type="text" 
                                   class="minimal-input @error('foreman_phone') is-invalid @enderror" 
                                   name="foreman_phone" 
                                   value="{{ old('foreman_phone', $project->foreman_phone) }}"
                                   placeholder="+7 (999) 123-45-67">
                            @error('foreman_phone')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">Формат: +7 (XXX) XXX-XX-XX</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Email прораба</label>
                            <input type="email" 
                                   class="minimal-input @error('foreman_email') is-invalid @enderror" 
                                   name="foreman_email" 
                                   value="{{ old('foreman_email', $project->foreman_email) }}"
                                   placeholder="foreman@example.com"
                                   maxlength="255">
                            @error('foreman_email')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group-minimal">
                    <label>Адрес проживания прораба</label>
                    <textarea class="minimal-input @error('foreman_address') is-invalid @enderror" 
                              name="foreman_address" 
                              rows="2"
                              style="resize: vertical;"
                              placeholder="г. Москва, ул. Строительная, д. 10, кв. 5">{{ old('foreman_address', $project->foreman_address) }}</textarea>
                    @error('foreman_address')
                        <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Паспортные данные прораба -->
        <div class="minimal-card mb-3">
            <div class="minimal-card-header">
                <span><i class="bi bi-card-text"></i> Паспортные данные прораба</span>
            </div>
            <div class="minimal-card-body">
                <p class="text-muted small mb-3">Необходимы для договоров и актов</p>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group-minimal">
                            <label>Серия</label>
                            <input type="text" 
                                   class="minimal-input @error('foreman_passport_series') is-invalid @enderror" 
                                   name="foreman_passport_series" 
                                   value="{{ old('foreman_passport_series', $project->foreman_passport_series) }}"
                                   placeholder="1234"
                                   pattern="[0-9]{4}"
                                   maxlength="4">
                            @error('foreman_passport_series')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">4 цифры</small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group-minimal">
                            <label>Номер</label>
                            <input type="text" 
                                   class="minimal-input @error('foreman_passport_number') is-invalid @enderror" 
                                   name="foreman_passport_number" 
                                   value="{{ old('foreman_passport_number', $project->foreman_passport_number) }}"
                                   placeholder="567890"
                                   pattern="[0-9]{6}"
                                   maxlength="6">
                            @error('foreman_passport_number')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">6 цифр</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Дата выдачи</label>
                            <input type="text" 
                                   class="minimal-input flatpickr-single @error('foreman_passport_issued_date') is-invalid @enderror" 
                                   id="foreman_passport_issued_date" 
                                   name="foreman_passport_issued_date" 
                                   value="{{ old('foreman_passport_issued_date', $project->foreman_passport_issued_date ? $project->foreman_passport_issued_date->format('d.m.Y') : '') }}"
                                   placeholder="Выберите дату">
                            @error('foreman_passport_issued_date')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group-minimal">
                    <label>Кем выдан</label>
                    <input type="text" 
                           class="minimal-input @error('foreman_passport_issued_by') is-invalid @enderror" 
                           name="foreman_passport_issued_by" 
                           value="{{ old('foreman_passport_issued_by', $project->foreman_passport_issued_by) }}"
                           placeholder="Отделом УФМС России по г. Москва"
                           maxlength="255">
                    @error('foreman_passport_issued_by')
                        <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Юридические данные прораба -->
        <div class="minimal-card mb-3">
            <div class="minimal-card-header">
                <span><i class="bi bi-building"></i> Для юридических лиц прораба (необязательно)</span>
            </div>
            <div class="minimal-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>Название организации</label>
                            <input type="text" 
                                   class="minimal-input @error('foreman_organization_name') is-invalid @enderror" 
                                   name="foreman_organization_name" 
                                   value="{{ old('foreman_organization_name', $project->foreman_organization_name) }}"
                                   placeholder='ООО "СтройСервис"'
                                   maxlength="255">
                            @error('foreman_organization_name')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-minimal">
                            <label>ИНН прораба</label>
                            <input type="text" 
                                   class="minimal-input @error('foreman_inn') is-invalid @enderror" 
                                   name="foreman_inn" 
                                   value="{{ old('foreman_inn', $project->foreman_inn) }}"
                                   placeholder="1234567890 или 123456789012"
                                   pattern="[0-9]{10}|[0-9]{12}"
                                   maxlength="12">
                            @error('foreman_inn')
                                <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                            <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">10 цифр (юр. лицо) или 12 цифр (физ. лицо)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Кнопки -->
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('projects.show', $project) }}" class="minimal-btn minimal-btn-ghost">
                <i class="bi bi-arrow-left"></i> Отмена
            </a>
            <button type="submit" class="minimal-btn minimal-btn-primary">
                <i class="bi bi-check-lg"></i> Сохранить данные
            </button>
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <strong>Важно:</strong> После заполнения этих данных вы сможете генерировать готовые документы для клиента 
            (сметы, договоры, акты и т.д.) с автоматической подстановкой информации.
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация Flatpickr для дат паспортов
    const passportDateInputs = document.querySelectorAll('.flatpickr-single');
    passportDateInputs.forEach(input => {
        if (input) {
            flatpickr(input, {
                locale: 'ru',
                dateFormat: 'd.m.Y',
                maxDate: 'today'
            });
        }
    });
});
</script>
@endsection
