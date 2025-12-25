<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Смета - <?php echo e($project->name); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2d3748;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 24px;
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .project-info {
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .project-info p {
            margin: 5px 0;
            line-height: 1.6;
        }
        
        .project-info strong {
            color: #2d3748;
        }
        
        .stage {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .stage-header {
            background: #2d3748;
            color: white;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        
        .stage-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 10px;
            margin-left: 10px;
            background: #48bb78;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        table th {
            background: #e2e8f0;
            color: #2d3748;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #cbd5e0;
            font-size: 11px;
        }
        
        table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
        }
        
        table tr:nth-child(even) {
            background: #f7fafc;
        }
        
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #2d3748;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #cbd5e0;
        }
        
        .subtotal {
            background: #edf2f7 !important;
            font-weight: bold;
        }
        
        .grand-total {
            margin-top: 30px;
            padding: 15px;
            background: #2d3748;
            color: white;
            border-radius: 5px;
        }
        
        .grand-total table {
            margin: 0;
        }
        
        .grand-total td {
            border: none;
            color: white;
            padding: 5px 10px;
            font-size: 13px;
        }
        
        .grand-total .total-row {
            font-size: 16px;
            font-weight: bold;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #cbd5e0;
            font-size: 10px;
            color: #718096;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>СМЕТА</h1>
        <p><?php echo e($project->name); ?></p>
    </div>

    <div class="project-info">
        <p><strong>Адрес объекта:</strong> <?php echo e($project->address); ?></p>
        <?php if($project->work_type): ?>
            <p><strong>Тип работ:</strong> <?php echo e($project->work_type); ?></p>
        <?php endif; ?>
        <p><strong>Статус проекта:</strong> <?php echo e($project->status); ?></p>
        <p><strong>Дата формирования сметы:</strong> <?php echo e(now()->format('d.m.Y')); ?></p>
    </div>

    <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stageData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="stage">
            <div class="stage-header">
                <?php echo e($stageData['name']); ?>

                <span class="stage-status"><?php echo e($stageData['status']); ?></span>
            </div>

            <?php if(count($stageData['tasks']) > 0): ?>
                <div class="section-title">Работы</div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%">№</th>
                            <th style="width: 35%">Наименование работы</th>
                            <th style="width: 25%">Описание</th>
                            <th style="width: 20%">Исполнитель</th>
                            <th style="width: 15%" class="text-right">Стоимость, ₽</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $stageData['tasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($index + 1); ?></td>
                                <td><?php echo e($task['name']); ?></td>
                                <td><?php echo e($task['description'] ?? '—'); ?></td>
                                <td><?php echo e($task['assigned_to']); ?></td>
                                <td class="text-right"><?php echo e(number_format($task['cost'], 2, ',', ' ')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr class="subtotal">
                            <td colspan="4" class="text-right">Итого работы:</td>
                            <td class="text-right"><?php echo e(number_format($stageData['stage_tasks_cost'], 2, ',', ' ')); ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if(count($stageData['materials']) > 0): ?>
                <div class="section-title">Материалы</div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%">№</th>
                            <th style="width: 30%">Наименование</th>
                            <th style="width: 25%">Описание</th>
                            <th style="width: 10%" class="text-center">Ед. изм.</th>
                            <th style="width: 10%" class="text-right">Кол-во</th>
                            <th style="width: 10%" class="text-right">Цена, ₽</th>
                            <th style="width: 10%" class="text-right">Сумма, ₽</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $stageData['materials']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($index + 1); ?></td>
                                <td><?php echo e($material['name']); ?></td>
                                <td><?php echo e($material['description'] ?? '—'); ?></td>
                                <td class="text-center"><?php echo e($material['unit']); ?></td>
                                <td class="text-right"><?php echo e(number_format($material['quantity'], 2, ',', ' ')); ?></td>
                                <td class="text-right"><?php echo e(number_format($material['price_per_unit'], 2, ',', ' ')); ?></td>
                                <td class="text-right"><?php echo e(number_format($material['total_cost'], 2, ',', ' ')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr class="subtotal">
                            <td colspan="6" class="text-right">Итого материалы:</td>
                            <td class="text-right"><?php echo e(number_format($stageData['stage_materials_cost'], 2, ',', ' ')); ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if(count($stageData['deliveries']) > 0): ?>
                <div class="section-title">Доставки</div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%">№</th>
                            <th style="width: 30%">Наименование</th>
                            <th style="width: 25%">Описание</th>
                            <th style="width: 10%" class="text-center">Ед. изм.</th>
                            <th style="width: 10%" class="text-right">Кол-во</th>
                            <th style="width: 10%" class="text-right">Цена, ₽</th>
                            <th style="width: 10%" class="text-right">Сумма, ₽</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $stageData['deliveries']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($index + 1); ?></td>
                                <td><?php echo e($delivery['name']); ?></td>
                                <td><?php echo e($delivery['description'] ?? '—'); ?></td>
                                <td class="text-center"><?php echo e($delivery['unit']); ?></td>
                                <td class="text-right"><?php echo e(number_format($delivery['quantity'], 2, ',', ' ')); ?></td>
                                <td class="text-right"><?php echo e(number_format($delivery['price_per_unit'], 2, ',', ' ')); ?></td>
                                <td class="text-right"><?php echo e(number_format($delivery['total_cost'], 2, ',', ' ')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr class="subtotal">
                            <td colspan="6" class="text-right">Итого доставки:</td>
                            <td class="text-right"><?php echo e(number_format($stageData['stage_deliveries_cost'], 2, ',', ' ')); ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if($stageData['stage_total'] > 0): ?>
                <table>
                    <tr class="subtotal">
                        <td style="width: 85%" class="text-right"><strong>Итого по этапу "<?php echo e($stageData['name']); ?>":</strong></td>
                        <td style="width: 15%" class="text-right"><strong><?php echo e(number_format($stageData['stage_total'], 2, ',', ' ')); ?> ₽</strong></td>
                    </tr>
                </table>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="grand-total">
        <table>
            <tr>
                <td style="width: 70%">Всего работы:</td>
                <td class="text-right"><?php echo e(number_format($total_tasks_cost, 2, ',', ' ')); ?> ₽</td>
            </tr>
            <tr>
                <td>Всего материалы:</td>
                <td class="text-right"><?php echo e(number_format($total_materials_cost, 2, ',', ' ')); ?> ₽</td>
            </tr>
            <tr>
                <td>Всего доставки:</td>
                <td class="text-right"><?php echo e(number_format($total_deliveries_cost, 2, ',', ' ')); ?> ₽</td>
            </tr>
            <tr class="total-row">
                <td><strong>ИТОГО ПО ПРОЕКТУ:</strong></td>
                <td class="text-right"><strong><?php echo e(number_format($grand_total, 2, ',', ' ')); ?> ₽</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Документ сформирован автоматически системой "<?php echo e(config('app.name')); ?>"</p>
        <p><?php echo e(now()->format('d.m.Y H:i')); ?></p>
    </div>
</body>
</html>
<?php /**PATH C:\OSPanel\domains\work\resources\views\estimates\pdf.blade.php ENDPATH**/ ?>