<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EstimateExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $rows = collect();
        
        // Заголовок сметы
        $rows->push(['СМЕТА', '', '', '', '', '', '']);
        $rows->push([$this->data['project']->name, '', '', '', '', '', '']);
        $rows->push(['Адрес: ' . $this->data['project']->address, '', '', '', '', '', '']);
        $rows->push(['Дата: ' . now()->format('d.m.Y'), '', '', '', '', '', '']);
        $rows->push(['', '', '', '', '', '', '']); // Пустая строка

        $rowNumber = 6;

        foreach ($this->data['stages'] as $stageData) {
            // Заголовок этапа
            $rows->push([
                'ЭТАП: ' . $stageData['name'] . ' (' . $stageData['status'] . ')',
                '', '', '', '', '', ''
            ]);
            $rowNumber++;

            // Работы
            if (count($stageData['tasks']) > 0) {
                $rows->push(['РАБОТЫ', '', '', '', '', '', '']);
                $rowNumber++;
                
                $rows->push(['№', 'Наименование', 'Описание', 'Исполнитель', 'Стоимость, ₽', '', '']);
                $rowNumber++;

                foreach ($stageData['tasks'] as $index => $task) {
                    $rows->push([
                        $index + 1,
                        $task['name'],
                        $task['description'] ?? '—',
                        $task['assigned_to'],
                        $task['cost'],
                        '', ''
                    ]);
                    $rowNumber++;
                }

                $rows->push(['', '', '', 'Итого работы:', $stageData['stage_tasks_cost'], '', '']);
                $rowNumber++;
                $rows->push(['', '', '', '', '', '', '']); // Пустая строка
                $rowNumber++;
            }

            // Материалы
            if (count($stageData['materials']) > 0) {
                $rows->push(['МАТЕРИАЛЫ', '', '', '', '', '', '']);
                $rowNumber++;
                
                $rows->push(['№', 'Наименование', 'Описание', 'Ед. изм.', 'Кол-во', 'Цена, ₽', 'Сумма, ₽']);
                $rowNumber++;

                foreach ($stageData['materials'] as $index => $material) {
                    $rows->push([
                        $index + 1,
                        $material['name'],
                        $material['description'] ?? '—',
                        $material['unit'],
                        $material['quantity'],
                        $material['price_per_unit'],
                        $material['total_cost']
                    ]);
                    $rowNumber++;
                }

                $rows->push(['', '', '', '', '', 'Итого материалы:', $stageData['stage_materials_cost']]);
                $rowNumber++;
                $rows->push(['', '', '', '', '', '', '']); // Пустая строка
                $rowNumber++;
            }

            // Доставки
            if (count($stageData['deliveries']) > 0) {
                $rows->push(['ДОСТАВКИ', '', '', '', '', '', '']);
                $rowNumber++;
                
                $rows->push(['№', 'Наименование', 'Описание', 'Ед. изм.', 'Кол-во', 'Цена, ₽', 'Сумма, ₽']);
                $rowNumber++;

                foreach ($stageData['deliveries'] as $index => $delivery) {
                    $rows->push([
                        $index + 1,
                        $delivery['name'],
                        $delivery['description'] ?? '—',
                        $delivery['unit'],
                        $delivery['quantity'],
                        $delivery['price_per_unit'],
                        $delivery['total_cost']
                    ]);
                    $rowNumber++;
                }

                $rows->push(['', '', '', '', '', 'Итого доставки:', $stageData['stage_deliveries_cost']]);
                $rowNumber++;
                $rows->push(['', '', '', '', '', '', '']); // Пустая строка
                $rowNumber++;
            }

            // Итого по этапу
            $rows->push(['', '', '', '', '', 'ИТОГО ПО ЭТАПУ:', $stageData['stage_total']]);
            $rowNumber++;
            $rows->push(['', '', '', '', '', '', '']); // Пустая строка
            $rowNumber++;
        }

        // Итоги
        $rows->push(['', '', '', '', '', '', '']);
        $rows->push(['', '', '', '', '', 'Всего работы:', $this->data['total_tasks_cost']]);
        $rows->push(['', '', '', '', '', 'Всего материалы:', $this->data['total_materials_cost']]);
        $rows->push(['', '', '', '', '', 'Всего доставки:', $this->data['total_deliveries_cost']]);
        $rows->push(['', '', '', '', '', 'ИТОГО ПО ПРОЕКТУ:', $this->data['grand_total']]);

        return $rows;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }

    public function title(): string
    {
        return 'Смета';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 30,
            'C' => 35,
            'D' => 20,
            'E' => 15,
            'F' => 15,
            'G' => 15,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Автоматическая высота строк
                foreach ($sheet->getRowIterator() as $row) {
                    $sheet->getRowDimension($row->getRowIndex())->setRowHeight(-1);
                }

                // Формат для денежных значений
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('E6:G' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');

                // Перенос текста
                $sheet->getStyle('A1:G' . $highestRow)
                    ->getAlignment()
                    ->setWrapText(true);

                // Границы для всей таблицы
                $sheet->getStyle('A5:G' . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}
