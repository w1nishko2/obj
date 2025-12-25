<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Типы документов
    const TYPE_ESTIMATE = 'estimate'; // Смета
    const TYPE_CONTRACT = 'contract'; // Договор подряда
    const TYPE_ACT = 'act'; // Акт выполненных работ
    const TYPE_KS2 = 'ks2'; // Акт о приёмке выполненных работ (КС-2)
    const TYPE_KS3 = 'ks3'; // Справка о стоимости выполненных работ (КС-3)
    const TYPE_HIDDEN_WORKS = 'hidden_works'; // Акт скрытых работ
    const TYPE_SCHEDULE = 'schedule'; // График производства работ
    const TYPE_TECH_TASK = 'tech_task'; // Техническое задание
    const TYPE_WARRANTY = 'warranty'; // Гарантийное письмо
    const TYPE_RECEIPT = 'receipt'; // Расписка об оплате
    const TYPE_INVOICE = 'invoice'; // Счет

    public static function getTypes()
    {
        return [
            self::TYPE_CONTRACT => 'Договор подряда (ГК РФ)',
            self::TYPE_TECH_TASK => 'Техническое задание',
            self::TYPE_ESTIMATE => 'Локальная смета',
            self::TYPE_SCHEDULE => 'График производства работ',
            self::TYPE_KS2 => 'Акт о приёмке работ (КС-2)',
            self::TYPE_KS3 => 'Справка о стоимости работ (КС-3)',
            self::TYPE_ACT => 'Акт приёма-передачи',
            self::TYPE_HIDDEN_WORKS => 'Акт освидетельствования скрытых работ',
            self::TYPE_WARRANTY => 'Гарантийное письмо',
            self::TYPE_INVOICE => 'Счёт на оплату',
            self::TYPE_RECEIPT => 'Расписка об оплате',
        ];
    }
}
