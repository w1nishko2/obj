<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentTemplate;

class DocumentTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Договор подряда (соответствует ГК РФ)',
                'type' => DocumentTemplate::TYPE_CONTRACT,
                'description' => 'Договор строительного подряда согласно главе 37 ГК РФ',
                'is_active' => true,
                'content' => $this->getContractTemplate(),
            ],
            [
                'name' => 'Техническое задание',
                'type' => DocumentTemplate::TYPE_TECH_TASK,
                'description' => 'Приложение к договору с детальным описанием работ',
                'is_active' => true,
                'content' => $this->getTechTaskTemplate(),
            ],
            [
                'name' => 'Локальная смета',
                'type' => DocumentTemplate::TYPE_ESTIMATE,
                'description' => 'Детальная смета с перечнем работ и материалов',
                'is_active' => true,
                'content' => $this->getEstimateTemplate(),
            ],
            [
                'name' => 'График производства работ',
                'type' => DocumentTemplate::TYPE_SCHEDULE,
                'description' => 'Календарный план выполнения работ по этапам',
                'is_active' => true,
                'content' => $this->getScheduleTemplate(),
            ],
            [
                'name' => 'Акт о приёмке выполненных работ (КС-2)',
                'type' => DocumentTemplate::TYPE_KS2,
                'description' => 'Унифицированная форма КС-2 для строительных работ',
                'is_active' => true,
                'content' => $this->getKS2Template(),
            ],
            [
                'name' => 'Справка о стоимости работ (КС-3)',
                'type' => DocumentTemplate::TYPE_KS3,
                'description' => 'Унифицированная форма КС-3, прилагается к КС-2',
                'is_active' => true,
                'content' => $this->getKS3Template(),
            ],
            [
                'name' => 'Акт приёма-передачи',
                'type' => DocumentTemplate::TYPE_ACT,
                'description' => 'Акт приёма-передачи выполненных работ',
                'is_active' => true,
                'content' => $this->getActTemplate(),
            ],
            [
                'name' => 'Акт освидетельствования скрытых работ',
                'type' => DocumentTemplate::TYPE_HIDDEN_WORKS,
                'description' => 'Акт для скрытых работ (фундамент, коммуникации и т.д.)',
                'is_active' => true,
                'content' => $this->getHiddenWorksTemplate(),
            ],
            [
                'name' => 'Гарантийное письмо',
                'type' => DocumentTemplate::TYPE_WARRANTY,
                'description' => 'Гарантийные обязательства подрядчика',
                'is_active' => true,
                'content' => $this->getWarrantyTemplate(),
            ],
            [
                'name' => 'Счёт на оплату',
                'type' => DocumentTemplate::TYPE_INVOICE,
                'description' => 'Счёт для оплаты работ',
                'is_active' => true,
                'content' => $this->getInvoiceTemplate(),
            ],
            [
                'name' => 'Расписка об оплате',
                'type' => DocumentTemplate::TYPE_RECEIPT,
                'description' => 'Расписка в получении денежных средств',
                'is_active' => true,
                'content' => $this->getReceiptTemplate(),
            ],
        ];

        foreach ($templates as $template) {
            DocumentTemplate::updateOrCreate(
                ['type' => $template['type']],
                $template
            );
        }
    }

    private function getTechTaskTemplate()
    {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Техническое задание</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; }
        h1 { font-size: 14px; margin: 0; }
        h3 { font-size: 12px; margin-top: 15px; margin-bottom: 8px; }
        .info { margin-bottom: 15px; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ТЕХНИЧЕСКОЕ ЗАДАНИЕ</h1>
        <p>Приложение № 1 к Договору подряда от {{current_date}}</p>
    </div>

    <div class="info">
        <p><strong>Объект:</strong> {{project_address}}</p>
        <p><strong>Заказчик:</strong> {{client_full_name}}</p>
        <p><strong>Подрядчик:</strong> {{foreman_name}}</p>
    </div>

    <h3>1. ОБЩИЕ ПОЛОЖЕНИЯ</h3>
    <p>1.1. Настоящее Техническое задание определяет объём, характер и последовательность работ по проекту "{{project_name}}".</p>
    <p>1.2. Вид работ: {{project_work_type}}</p>

    <h3>2. СОСТАВ И СОДЕРЖАНИЕ РАБОТ</h3>
    {{stages_table}}

    <h3>3. ТРЕБОВАНИЯ К КАЧЕСТВУ РАБОТ</h3>
    <p>3.1. Все работы должны выполняться в соответствии со СНиП и действующими строительными нормами РФ.</p>
    <p>3.2. Используемые материалы должны иметь сертификаты качества.</p>
    <p>3.3. Подрядчик обеспечивает соблюдение технологии производства работ.</p>

    <h3>4. ПОРЯДОК СДАЧИ-ПРИЁМКИ РАБОТ</h3>
    <p>4.1. Приёмка работ производится поэтапно согласно графику производства работ.</p>
    <p>4.2. При приёмке составляется Акт выполненных работ.</p>

    <div style="margin-top: 30px; font-size: 10px;">
        <p><strong>Подрядчик:</strong> ______________ ({{foreman_name}})</p>
        <p style="margin-top: 20px;"><strong>Заказчик:</strong> ______________ ({{client_full_name}})</p>
    </div>
</body>
</html>';
    }

    private function getScheduleTemplate()
    {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>График производства работ</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        h1 { font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; font-size: 10px; }
        th { background: #e0e0e0; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ГРАФИК ПРОИЗВОДСТВА РАБОТ</h1>
        <p>Приложение № 2 к Договору подряда от {{current_date}}</p>
    </div>

    <div style="margin-bottom: 15px;">
        <p><strong>Объект:</strong> {{project_address}}</p>
        <p><strong>Проект:</strong> {{project_name}}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">№</th>
                <th width="40%">Наименование этапа работ</th>
                <th width="15%">Начало</th>
                <th width="15%">Окончание</th>
                <th width="25%">Ответственный</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5" style="text-align: center; font-style: italic;">
                    График заполняется на основании этапов проекта
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 10px;">
        <p><strong>Примечание:</strong> График может корректироваться по согласованию Сторон.</p>
    </div>

    <div style="margin-top: 30px; font-size: 10px;">
        <p><strong>Подрядчик:</strong> ______________ ({{foreman_name}})</p>
        <p style="margin-top: 20px;"><strong>Заказчик:</strong> ______________ ({{client_full_name}})</p>
    </div>
</body>
</html>';
    }

    private function getEstimateTemplate()
    {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Смета</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .info { margin-bottom: 20px; }
        .info p { margin: 5px 0; }
        h1 { font-size: 18px; margin: 0; }
        .signatures { margin-top: 50px; }
        .signature { display: inline-block; width: 45%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>СМЕТА НА ВЫПОЛНЕНИЕ РАБОТ</h1>
        <p>№ {{project_name}} от {{current_date}}</p>
    </div>

    <div class="info">
        <p><strong>Заказчик:</strong> {{client_full_name}}</p>
        <p><strong>Адрес объекта:</strong> {{project_address}}</p>
        <p><strong>Тип работ:</strong> {{project_work_type}}</p>
        <p><strong>Исполнитель:</strong> {{foreman_name}}</p>
        <p><strong>Телефон исполнителя:</strong> {{foreman_phone}}</p>
    </div>

    <h3>Перечень работ и материалов:</h3>
    {{stages_table}}

    <div style="margin-top: 30px;">
        <p><strong>Общая стоимость работ: {{total_cost}} рублей</strong></p>
    </div>

    <div class="signatures">
        <div class="signature">
            <p>Исполнитель: ____________________</p>
            <p style="margin-top: 5px;">{{foreman_name}}</p>
        </div>
        <div class="signature" style="float: right;">
            <p>Заказчик: ____________________</p>
            <p style="margin-top: 5px;">{{client_full_name}}</p>
        </div>
    </div>
</body>
</html>';
    }

    private function getKS2Template()
    {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Акт КС-2</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 15px; }
        h1 { font-size: 12px; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #000; padding: 4px; font-size: 9px; }
        th { background: #e0e0e0; text-align: center; }
        .sign-table { border: none; margin-top: 20px; }
        .sign-table td { border: none; padding: 2px; }
    </style>
</head>
<body>
    <div style="text-align: right; font-size: 9px; margin-bottom: 10px;">
        <p style="margin: 2px 0;">Унифицированная форма № КС-2</p>
        <p style="margin: 2px 0;">Утверждена Постановлением Госкомстата РФ от 11.11.1999 № 100</p>
    </div>

    <div class="header">
        <h1>АКТ О ПРИЁМКЕ ВЫПОЛНЕННЫХ РАБОТ № ___</h1>
        <p>от {{current_date}}</p>
    </div>

    <table style="border: none; margin-bottom: 15px;">
        <tr>
            <td style="border: none; width: 20%;"><strong>Заказчик:</strong></td>
            <td style="border: none;">{{client_full_name}}</td>
        </tr>
        <tr>
            <td style="border: none;"><strong>Подрядчик:</strong></td>
            <td style="border: none;">{{foreman_name}}</td>
        </tr>
        <tr>
            <td style="border: none;"><strong>Объект:</strong></td>
            <td style="border: none;">{{project_address}}</td>
        </tr>
        <tr>
            <td style="border: none;"><strong>Договор подряда:</strong></td>
            <td style="border: none;">от {{project_created_date}}</td>
        </tr>
    </table>

    <p style="margin: 10px 0; font-size: 10px;">Приёмочная комиссия в составе представителей:</p>
    <p style="margin: 5px 0;">Заказчика: {{client_full_name}}</p>
    <p style="margin: 5px 0;">Подрядчика: {{foreman_name}}</p>

    <h3 style="font-size: 11px; margin: 15px 0 10px 0;">составила настоящий акт о нижеследующем:</h3>

    <table>
        <thead>
            <tr>
                <th rowspan="2" width="5%">№</th>
                <th rowspan="2" width="40%">Наименование работ</th>
                <th rowspan="2" width="10%">Ед. изм.</th>
                <th colspan="2">Выполнено работ</th>
                <th rowspan="2" width="15%">Стоимость, руб.</th>
            </tr>
            <tr>
                <th width="10%">Кол-во</th>
                <th width="10%">Цена, руб.</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" style="text-align: center; font-style: italic;">
                    Данные из этапов проекта
                </td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 15px;"><strong>Итого стоимость выполненных работ: {{total_cost}} руб.</strong></p>

    <p style="margin: 15px 0; font-size: 10px;">Вышеперечисленные работы выполнены полностью, с надлежащим качеством, в соответствии с договором подряда.</p>

    <table class="sign-table">
        <tr>
            <td width="50%" style="padding-right: 20px;">
                <p><strong>Подрядчик:</strong></p>
                <p>{{foreman_name}}</p>
                <p style="margin-top: 30px;">Подпись: ________________</p>
            </td>
            <td width="50%">
                <p><strong>Заказчик:</strong></p>
                <p>{{client_full_name}}</p>
                <p style="margin-top: 30px;">Подпись: ________________</p>
            </td>
        </tr>
    </table>
</body>
</html>';
    }

    private function getKS3Template()
    {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Справка КС-3</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 15px; }
        h1 { font-size: 12px; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #000; padding: 4px; font-size: 9px; }
        th { background: #e0e0e0; text-align: center; }
    </style>
</head>
<body>
    <div style="text-align: right; font-size: 9px; margin-bottom: 10px;">
        <p style="margin: 2px 0;">Унифицированная форма № КС-3</p>
        <p style="margin: 2px 0;">Утверждена Постановлением Госкомстата РФ от 11.11.1999 № 100</p>
    </div>

    <div class="header">
        <h1>СПРАВКА О СТОИМОСТИ ВЫПОЛНЕННЫХ РАБОТ И ЗАТРАТ № ___</h1>
        <p>от {{current_date}}</p>
    </div>

    <table style="border: none; margin-bottom: 15px;">
        <tr>
            <td style="border: none; width: 20%;"><strong>Заказчик:</strong></td>
            <td style="border: none;">{{client_full_name}}</td>
        </tr>
        <tr>
            <td style="border: none;"><strong>Подрядчик:</strong></td>
            <td style="border: none;">{{foreman_name}}</td>
        </tr>
        <tr>
            <td style="border: none;"><strong>Объект:</strong></td>
            <td style="border: none;">{{project_address}}</td>
        </tr>
        <tr>
            <td style="border: none;"><strong>Договор подряда:</strong></td>
            <td style="border: none;">от {{project_created_date}}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="10%">№</th>
                <th width="60%">Наименование работ и затрат</th>
                <th width="30%">Сумма, руб.</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Выполнено работ (согласно Акту КС-2)</td>
                <td style="text-align: right;">{{total_cost}}</td>
            </tr>
            <tr style="background: #f0f0f0; font-weight: bold;">
                <td colspan="2" style="text-align: right;">ИТОГО:</td>
                <td style="text-align: right;">{{total_cost}}</td>
            </tr>
        </tbody>
    </table>

    <p style="margin: 15px 0;"><strong>Всего к оплате: {{total_cost}} рублей</strong></p>

    <div style="margin-top: 30px; font-size: 10px;">
        <table class="sign-table" style="border: none;">
            <tr>
                <td width="50%" style="border: none; padding-right: 20px;">
                    <p><strong>Подрядчик:</strong></p>
                    <p>{{foreman_name}}</p>
                    <p style="margin-top: 30px;">Подпись: ________________</p>
                </td>
                <td width="50%" style="border: none;">
                    <p><strong>Заказчик:</strong></p>
                    <p>{{client_full_name}}</p>
                    <p style="margin-top: 30px;">Подпись: ________________</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>';
    }

    private function getHiddenWorksTemplate()
    {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Акт скрытых работ</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; }
        h1 { font-size: 13px; margin: 5px 0; }
        .info { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>АКТ ОСВИДЕТЕЛЬСТВОВАНИЯ СКРЫТЫХ РАБОТ № ___</h1>
        <p>от {{current_date}}</p>
    </div>

    <div class="info">
        <p><strong>Объект:</strong> {{project_address}}</p>
        <p><strong>Наименование работ:</strong> {{project_work_type}}</p>
    </div>

    <p>Комиссия в составе:</p>
    <p><strong>От Заказчика:</strong> {{client_full_name}}</p>
    <p><strong>От Подрядчика:</strong> {{foreman_name}}</p>

    <h3 style="margin-top: 20px;">произвела осмотр скрытых работ и установила:</h3>

    <p style="margin: 15px 0;">1. Скрытые работы выполнены в соответствии с проектной документацией, требованиями СНиП и технологией производства работ.</p>

    <p>2. При выполнении работ использовались материалы, соответствующие проектным спецификациям и имеющие сертификаты качества.</p>

    <p>3. Отклонений от проекта и нарушений не обнаружено.</p>

    <h3 style="margin-top: 20px;">ЗАКЛЮЧЕНИЕ:</h3>
    <p>Выполненные скрытые работы принимаются. Разрешается производство последующих работ.</p>

    <div style="margin-top: 40px;">
        <p><strong>Подрядчик:</strong></p>
        <p>{{foreman_name}}</p>
        <p style="margin-top: 15px;">Подпись: ________________ Дата: {{current_date}}</p>
    </div>

    <div style="margin-top: 30px;">
        <p><strong>Заказчик:</strong></p>
        <p>{{client_full_name}}</p>
        <p style="margin-top: 15px;">Подпись: ________________ Дата: {{current_date}}</p>
    </div>
</body>
</html>';
    }

    private function getWarrantyTemplate()
    {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Гарантийное письмо</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; }
        h1 { font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ГАРАНТИЙНОЕ ПИСЬМО</h1>
        <p>от {{current_date}}</p>
    </div>

    <div style="margin-bottom: 20px;">
        <p><strong>Заказчику:</strong> {{client_full_name}}</p>
        <p><strong>От Подрядчика:</strong> {{foreman_name}}</p>
    </div>

    <p>Настоящим гарантируем качество выполненных работ по объекту: {{project_address}}.</p>

    <h3>ГАРАНТИЙНЫЕ ОБЯЗАТЕЛЬСТВА:</h3>

    <p>1. Подрядчик гарантирует качество выполненных работ в соответствии с договором подряда от {{project_created_date}}.</p>

    <p>2. <strong>Гарантийный срок составляет 12 (двенадцать) месяцев</strong> с момента подписания Акта приёма-передачи выполненных работ.</p>

    <p>3. В течение гарантийного срока Подрядчик обязуется:</p>
    <ul>
        <li>Безвозмездно устранять выявленные недостатки в выполненных работах, возникшие по вине Подрядчика;</li>
        <li>Реагировать на обращения Заказчика в течение 3 (трёх) рабочих дней;</li>
        <li>Устранять недостатки в согласованные с Заказчиком сроки.</li>
    </ul>

    <p>4. Гарантия не распространяется на дефекты, возникшие вследствие:</p>
    <ul>
        <li>Нарушения Заказчиком правил эксплуатации;</li>
        <li>Механических повреждений;</li>
        <li>Действия непреодолимой силы (форс-мажор);</li>
        <li>Несанкционированного вмешательства третьих лиц.</li>
    </ul>

    <p>5. Для вызова в гарантийный период Заказчик направляет письменную заявку по телефону: {{foreman_phone}} или email: {{foreman_email}}.</p>

    <div style="margin-top: 50px;">
        <p><strong>Подрядчик:</strong></p>
        <p>{{foreman_name}}</p>
        <p>Телефон: {{foreman_phone}}</p>
        <p>Email: {{foreman_email}}</p>
        <p style="margin-top: 20px;">Подпись: ____________________ Дата: {{current_date}}</p>
    </div>
</body>
</html>';
    }

    private function getContractTemplate()
    {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Договор подряда</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; }
        h1 { font-size: 13px; text-align: center; margin: 5px 0; }
        h3 { font-size: 11px; margin-top: 15px; margin-bottom: 8px; }
        .section { margin: 12px 0; }
        p { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ДОГОВОР СТРОИТЕЛЬНОГО ПОДРЯДА № ___</h1>
        <p>г. _____________ {{current_date}}</p>
    </div>

    <div class="section">
        <p><strong>ЗАКАЗЧИК:</strong> {{client_full_name}}, {{client_passport_issued_date}} года рождения,</p>
        <p>паспорт: серия {{client_passport_series}} № {{client_passport_number}}, выдан {{client_passport_issued_by}}, {{client_passport_issued_date}},</p>
        <p>зарегистрированный(ая) по адресу: {{client_address}}, тел.: {{client_phone}},</p>
        <p>именуемый(ая) в дальнейшем «Заказчик», с одной стороны, и</p>
        
        <p style="margin-top: 10px;"><strong>ПОДРЯДЧИК:</strong> {{foreman_name}}, тел.: {{foreman_phone}}, email: {{foreman_email}},</p>
        <p>именуемый в дальнейшем «Подрядчик», с другой стороны,</p>
        
        <p style="margin-top: 10px;">вместе именуемые «Стороны», заключили настоящий Договор о нижеследующем:</p>
    </div>

    <h3>1. ПРЕДМЕТ ДОГОВОРА</h3>
    <div class="section">
        <p>1.1. В соответствии со статьями 702, 740-757 Гражданского кодекса Российской Федерации Подрядчик обязуется в установленный настоящим Договором срок выполнить по заданию Заказчика работы:</p>
        <p style="padding-left: 15px;">- Наименование: {{project_name}}</p>
        <p style="padding-left: 15px;">- Вид работ: {{project_work_type}}</p>
        <p style="padding-left: 15px;">- Объект: {{project_address}}</p>
        <p>а Заказчик обязуется принять результат работы и оплатить его.</p>
        
        <p>1.2. Работы выполняются в соответствии с Техническим заданием (Приложение № 1) и Локальной сметой (Приложение № 2), которые являются неотъемлемой частью настоящего Договора.</p>
        
        <p>1.3. Качество работ должно соответствовать требованиям СНиП, ГОСТ и иным обязательным нормам.</p>
    </div>

    <h3>2. ЦЕНА ДОГОВОРА И ПОРЯДОК РАСЧЁТОВ</h3>
    <div class="section">
        <p>2.1. Цена настоящего Договора составляет {{total_cost}} (____________________) рублей и включает стоимость работ и материалов.</p>
        
        <p>2.2. Оплата производится Заказчиком поэтапно, согласно Графику производства работ (Приложение № 3):</p>
        <p style="padding-left: 15px;">- после выполнения каждого этапа работ на основании подписанного Акта выполненных работ.</p>
        
        <p>2.3. Расчёты производятся наличными денежными средствами или безналичным переводом на реквизиты Подрядчика.</p>
        
        <p>2.4. Цена Договора может быть изменена по соглашению Сторон в случае изменения объёма или характера работ.</p>
    </div>

    <h3>3. СРОКИ ВЫПОЛНЕНИЯ РАБОТ</h3>
    <div class="section">
        <p>3.1. Начало работ: {{project_created_date}}</p>
        <p>3.2. Окончание работ: согласно Графику производства работ (Приложение № 3).</p>
        <p>3.3. Сроки выполнения работ могут быть продлены по соглашению Сторон или по основаниям, предусмотренным законодательством РФ.</p>
    </div>

    <h3>4. ОБЯЗАННОСТИ ПОДРЯДЧИКА</h3>
    <div class="section">
        <p>4.1. Выполнить работы качественно, в полном объёме и в установленные сроки.</p>
        <p>4.2. Обеспечить сохранность имущества Заказчика на объекте.</p>
        <p>4.3. Использовать материалы, соответствующие техническим требованиям и имеющие сертификаты качества.</p>
        <p>4.4. Соблюдать технику безопасности и противопожарные правила.</p>
        <p>4.5. Своевременно информировать Заказчика о ходе выполнения работ.</p>
        <p>4.6. По требованию Заказчика предоставлять для проверки скрытые работы до их закрытия.</p>
        <p>4.7. Устранить выявленные недостатки в согласованные сроки.</p>
    </div>

    <h3>5. ОБЯЗАННОСТИ ЗАКАЗЧИКА</h3>
    <div class="section">
        <p>5.1. Обеспечить Подрядчику беспрепятственный доступ к объекту для производства работ.</p>
        <p>5.2. Оплачивать выполненные работы в порядке и сроки, установленные настоящим Договором.</p>
        <p>5.3. Осуществлять контроль за ходом и качеством выполняемых работ.</p>
        <p>5.4. Принять выполненные работы согласно условиям настоящего Договора.</p>
    </div>

    <h3>6. ПОРЯДОК СДАЧИ-ПРИЁМКИ РАБОТ</h3>
    <div class="section">
        <p>6.1. Сдача-приёмка работ осуществляется поэтапно по мере их завершения.</p>
        <p>6.2. По окончании каждого этапа работ Подрядчик уведомляет Заказчика о готовности к приёмке.</p>
        <p>6.3. Заказчик в течение 3 (трёх) рабочих дней производит осмотр и приёмку работ.</p>
        <p>6.4. При отсутствии претензий Стороны подписывают Акт выполненных работ.</p>
        <p>6.5. При обнаружении недостатков Заказчик составляет письменную претензию с указанием недостатков и сроков их устранения.</p>
    </div>

    <h3>7. ГАРАНТИИ КАЧЕСТВА</h3>
    <div class="section">
        <p>7.1. Подрядчик гарантирует качество выполненных работ в течение 12 (двенадцати) месяцев с момента подписания итогового Акта выполненных работ.</p>
        <p>7.2. В течение гарантийного срока Подрядчик обязуется безвозмездно устранить выявленные недостатки, возникшие не по вине Заказчика.</p>
        <p>7.3. Гарантия не распространяется на недостатки, возникшие вследствие нарушения Заказчиком правил эксплуатации, механических повреждений или действий третьих лиц.</p>
    </div>

    <h3>8. ОТВЕТСТВЕННОСТЬ СТОРОН</h3>
    <div class="section">
        <p>8.1. За нарушение сроков выполнения работ Подрядчик уплачивает Заказчику пеню в размере 0,1% от стоимости работ за каждый день просрочки.</p>
        <p>8.2. За нарушение сроков оплаты Заказчик уплачивает Подрядчику пеню в размере 0,1% от суммы задолженности за каждый день просрочки.</p>
        <p>8.3. Уплата неустойки не освобождает Стороны от исполнения обязательств по Договору.</p>
        <p>8.4. В случае причинения ущерба виновная Сторона возмещает его в соответствии с законодательством РФ.</p>
    </div>

    <h3>9. ФОРС-МАЖОР</h3>
    <div class="section">
        <p>9.1. Стороны освобождаются от ответственности за частичное или полное неисполнение обязательств, если оно явилось следствием обстоятельств непреодолимой силы (форс-мажор), возникших после заключения Договора.</p>
        <p>9.2. Сторона, для которой создалась невозможность исполнения обязательств, обязана немедленно известить другую Сторону.</p>
    </div>

    <h3>10. ПОРЯДОК РАЗРЕШЕНИЯ СПОРОВ</h3>
    <div class="section">
        <p>10.1. Все споры и разногласия решаются путём переговоров.</p>
        <p>10.2. При недостижении согласия споры разрешаются в судебном порядке по месту нахождения объекта.</p>
    </div>

    <h3>11. СРОК ДЕЙСТВИЯ И ПРОЧИЕ УСЛОВИЯ</h3>
    <div class="section">
        <p>11.1. Договор вступает в силу с момента подписания и действует до полного исполнения Сторонами своих обязательств.</p>
        <p>11.2. Изменения и дополнения к Договору действительны, если они совершены в письменной форме и подписаны обеими Сторонами.</p>
        <p>11.3. Договор может быть расторгнут по соглашению Сторон или в одностороннем порядке по основаниям, предусмотренным ГК РФ.</p>
        <p>11.4. Настоящий Договор составлен в двух экземплярах, имеющих равную юридическую силу, по одному для каждой Стороны.</p>
    </div>

    <h3>12. ПРИЛОЖЕНИЯ К ДОГОВОРУ</h3>
    <div class="section">
        <p>Приложение № 1 — Техническое задание</p>
        <p>Приложение № 2 — Локальная смета</p>
        <p>Приложение № 3 — График производства работ</p>
    </div>

    <h3 style="margin-top: 20px;">13. РЕКВИЗИТЫ И ПОДПИСИ СТОРОН</h3>
    <table style="width: 100%; border: none; margin-top: 15px;" cellpadding="5">
        <tr>
            <td style="width: 50%; vertical-align: top; border: none;">
                <p><strong>ПОДРЯДЧИК:</strong></p>
                <p>{{foreman_name}}</p>
                <p>Телефон: {{foreman_phone}}</p>
                <p>Email: {{foreman_email}}</p>
                <p style="margin-top: 40px;">_____________ / {{foreman_name}} /</p>
                <p style="font-size: 9px;">(подпись)</p>
            </td>
            <td style="width: 50%; vertical-align: top; border: none;">
                <p><strong>ЗАКАЗЧИК:</strong></p>
                <p>{{client_full_name}}</p>
                <p>Адрес: {{client_address}}</p>
                <p>Паспорт: {{client_passport_series}} {{client_passport_number}}</p>
                <p>Телефон: {{client_phone}}</p>
                <p style="margin-top: 20px;">_____________ / {{client_full_name}} /</p>
                <p style="font-size: 9px;">(подпись)</p>
            </td>
        </tr>
    </table>
</body>
</html>';
    }

    private function getActTemplate()
    {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Акт выполненных работ</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        h1 { font-size: 16px; }
        .info { margin-bottom: 20px; }
        .signatures { margin-top: 50px; }
        .signature-block { margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>АКТ ПРИЕМА-ПЕРЕДАЧИ ВЫПОЛНЕННЫХ РАБОТ</h1>
        <p>№ {{project_name}} от {{current_date}}</p>
    </div>

    <div class="info">
        <p>Мы, нижеподписавшиеся:</p>
        <p><strong>ПОДРЯДЧИК:</strong> {{foreman_name}}, тел.: {{foreman_phone}}</p>
        <p>и</p>
        <p><strong>ЗАКАЗЧИК:</strong> {{client_full_name}}, тел.: {{client_phone}}</p>
        <p>составили настоящий Акт о том, что Подрядчиком выполнены следующие работы:</p>
    </div>

    <h3>Перечень выполненных работ:</h3>
    {{stages_table}}

    <div style="margin-top: 30px;">
        <p><strong>Общая стоимость выполненных работ: {{total_cost}} рублей</strong></p>
    </div>

    <div style="margin-top: 20px;">
        <p>Работы выполнены полностью, в установленные сроки, с надлежащим качеством.</p>
        <p>Заказчик претензий к качеству и объему выполненных работ не имеет.</p>
    </div>

    <div class="signatures">
        <div class="signature-block">
            <p><strong>Работы сдал (ПОДРЯДЧИК):</strong></p>
            <p>{{foreman_name}}</p>
            <p>Подпись: ____________________ Дата: {{current_date}}</p>
        </div>

        <div class="signature-block">
            <p><strong>Работы принял (ЗАКАЗЧИК):</strong></p>
            <p>{{client_full_name}}</p>
            <p>Подпись: ____________________ Дата: {{current_date}}</p>
        </div>
    </div>
</body>
</html>';
    }

    private function getInvoiceTemplate()
    {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Счет на оплату</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 20px; }
        h1 { font-size: 18px; margin: 0; }
        .info { margin-bottom: 20px; }
        .info-row { display: flex; margin: 5px 0; }
        .label { font-weight: bold; width: 200px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>СЧЕТ НА ОПЛАТУ № {{project_name}}</h1>
        <p>от {{current_date}}</p>
    </div>

    <div class="info">
        <h3>Исполнитель:</h3>
        <p>{{foreman_name}}</p>
        <p>Телефон: {{foreman_phone}}</p>
        <p>Email: {{foreman_email}}</p>
    </div>

    <div class="info">
        <h3>Плательщик:</h3>
        <p>{{client_full_name}}</p>
        <p>Адрес: {{client_address}}</p>
        <p>Телефон: {{client_phone}}</p>
        <p>Email: {{client_email}}</p>
    </div>

    <h3>Основание:</h3>
    <p>Выполнение работ по проекту "{{project_name}}"</p>
    <p>Адрес объекта: {{project_address}}</p>
    <p>Вид работ: {{project_work_type}}</p>

    <h3>К оплате:</h3>
    {{stages_table}}

    <div style="margin-top: 30px; font-size: 16px;">
        <p><strong>ИТОГО К ОПЛАТЕ: {{total_cost}} рублей</strong></p>
    </div>

    <div style="margin-top: 50px;">
        <p>Счет действителен в течение 5 банковских дней.</p>
        <p>Оплата производится наличными или безналичным переводом.</p>
    </div>

    <div style="margin-top: 50px;">
        <p>Исполнитель: ____________________ {{foreman_name}}</p>
    </div>
</body>
</html>';
    }

    private function getReceiptTemplate()
    {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Квитанция об оплате</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        .receipt { border: 2px solid #000; padding: 30px; max-width: 600px; margin: 50px auto; }
        .header { text-align: center; margin-bottom: 30px; }
        h1 { font-size: 20px; margin: 0 0 10px 0; }
        .info { margin: 15px 0; }
        .amount { font-size: 18px; font-weight: bold; text-align: center; margin: 30px 0; padding: 20px; background: #f0f0f0; }
        .signatures { margin-top: 50px; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>КВИТАНЦИЯ ОБ ОПЛАТЕ</h1>
            <p>№ {{project_name}} от {{current_date}}</p>
        </div>

        <div class="info">
            <p><strong>Получатель платежа:</strong> {{foreman_name}}</p>
            <p><strong>Плательщик:</strong> {{client_full_name}}</p>
        </div>

        <div class="info">
            <p><strong>Назначение платежа:</strong></p>
            <p>Оплата за выполнение работ по проекту "{{project_name}}"</p>
            <p>Адрес: {{project_address}}</p>
        </div>

        <div class="amount">
            <p>СУММА: {{total_cost}} рублей</p>
        </div>

        <div class="info">
            <p>Сумма прописью: _________________________________ рублей</p>
        </div>

        <div class="signatures">
            <p>Получатель: ____________________ {{foreman_name}}</p>
            <p style="margin-top: 30px;">М.П. (при наличии)</p>
        </div>
    </div>
</body>
</html>';
    }
}
