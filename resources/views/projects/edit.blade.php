@extends('layouts.app')

@section('content')
<div class="minimal-container">
    <form action="{{ route('projects.update', $project) }}" method="POST" class="d-flex flex-column" style="min-height: calc(100vh - 80px);">
        @csrf
        @method('PUT')
        
        <div class="modal-header border-0 pb-2">
            <a href="{{ route('projects.show', $project) }}" class="btn-close position-absolute top-0 end-0 m-3" style="text-decoration: none;"></a>
        </div>
        
        <div class="px-4">
            <div class="wizard-header text-center">
                <h2>Редактировать проект</h2>
                <p>Измените параметры проекта</p>
            </div>
        </div>
        
        <div class="modal-body pt-0 d-flex align-items-center justify-content-center flex-grow-1">
            <div class="wizard-container" style="max-width: 600px; width: 100%;">
                
                <div class="form-group-minimal">
                    <label>Название проекта</label>
                    <input type="text" class="minimal-input @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name', $project->name) }}" 
                           placeholder="Ремонт квартиры на ул. Ленина"
                           maxlength="255"
                           required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-minimal">
                    <label>Адрес объекта</label>
                    <input type="text" class="minimal-input @error('address') is-invalid @enderror" 
                           name="address" value="{{ old('address', $project->address) }}" 
                           placeholder="ул. Ленина, д. 10, кв. 25"
                           maxlength="255"
                           required>
                    @error('address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-minimal">
                    <label>Тип работ</label>
                    <select class="minimal-input @error('work_type') is-invalid @enderror" 
                            name="work_type">
                        <option value="">Выберите тип работ</option>
                        <option value="Капитальный ремонт" {{ old('work_type', $project->work_type) == 'Капитальный ремонт' ? 'selected' : '' }}>Капитальный ремонт</option>
                        <option value="Косметический ремонт" {{ old('work_type', $project->work_type) == 'Косметический ремонт' ? 'selected' : '' }}>Косметический ремонт</option>
                        <option value="Строительство" {{ old('work_type', $project->work_type) == 'Строительство' ? 'selected' : '' }}>Строительство</option>
                        <option value="Реконструкция" {{ old('work_type', $project->work_type) == 'Реконструкция' ? 'selected' : '' }}>Реконструкция</option>
                        <option value="Отделочные работы" {{ old('work_type', $project->work_type) == 'Отделочные работы' ? 'selected' : '' }}>Отделочные работы</option>
                        <option value="Электромонтажные работы" {{ old('work_type', $project->work_type) == 'Электромонтажные работы' ? 'selected' : '' }}>Электромонтажные работы</option>
                        <option value="Сантехнические работы" {{ old('work_type', $project->work_type) == 'Сантехнические работы' ? 'selected' : '' }}>Сантехнические работы</option>
                        <option value="Кровельные работы" {{ old('work_type', $project->work_type) == 'Кровельные работы' ? 'selected' : '' }}>Кровельные работы</option>
                        <option value="Фасадные работы" {{ old('work_type', $project->work_type) == 'Фасадные работы' ? 'selected' : '' }}>Фасадные работы</option>
                        <option value="Ландшафтные работы" {{ old('work_type', $project->work_type) == 'Ландшафтные работы' ? 'selected' : '' }}>Ландшафтные работы</option>
                        <option value="Дизайн интерьера" {{ old('work_type', $project->work_type) == 'Дизайн интерьера' ? 'selected' : '' }}>Дизайн интерьера</option>
                        <option value="Другое" {{ old('work_type', $project->work_type) == 'Другое' ? 'selected' : '' }}>Другое</option>
                    </select>
                    @error('work_type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-minimal">
                    <label>Статус проекта</label>
                    <select class="minimal-input @error('status') is-invalid @enderror" 
                            name="status" required>
                        <option value="В работе" {{ old('status', $project->status) == 'В работе' ? 'selected' : '' }}>В работе</option>
                        <option value="На паузе" {{ old('status', $project->status) == 'На паузе' ? 'selected' : '' }}>На паузе</option>
                        <option value="Завершен" {{ old('status', $project->status) == 'Завершен' ? 'selected' : '' }}>Завершен</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-minimal">
                    <label>Общая наценка проекта (%)</label>
                    <input type="number" 
                           class="minimal-input @error('markup_percent') is-invalid @enderror" 
                           name="markup_percent" 
                           value="{{ old('markup_percent', $project->markup_percent) }}"
                           placeholder="Например: 20"
                           step="0.01"
                           min="0"
                           max="999.99">
                    <small class="form-text text-muted" style="font-size: 0.85rem; margin-top: 0.25rem; display: block;">
                        Общая наценка будет применена ко всем задачам, материалам и доставкам. Можно переопределить для конкретного элемента.
                    </small>
                    @error('markup_percent')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
        
        <div class="modal-footer border-0 justify-content-center">
            <a href="{{ route('projects.show', $project) }}" class="minimal-btn minimal-btn-ghost">Отмена</a>
            <button type="submit" class="minimal-btn minimal-btn-primary">Сохранить</button>
        </div>
    </form>
</div>
@endsection
