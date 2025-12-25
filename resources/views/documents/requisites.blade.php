@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="mb-4">Реквизиты</h2>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Индивидуальный предприниматель</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong class="text-muted">ФИО:</strong>
                        </div>
                        <div class="col-md-8">
                            Лукманов Даниил Равильевич
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong class="text-muted">ИНН:</strong>
                        </div>
                        <div class="col-md-8">
                            614107632605
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong class="text-muted">Почтовый индекс:</strong>
                        </div>
                        <div class="col-md-8">
                            346886
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Контактная информация</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong class="text-muted">Телефон:</strong>
                        </div>
                        <div class="col-md-8">
                            <a href="tel:+79044482283">+7 (904) 448-22-83</a>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong class="text-muted">Email:</strong>
                        </div>
                        <div class="col-md-8">
                            <a href="mailto:w1nishko@yandex.ru">w1nishko@yandex.ru</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
