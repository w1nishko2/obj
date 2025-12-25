<?php
// Скрипт для генерации простых иконок PWA с помощью PHP GD

$sizes = [72, 96, 128, 144, 152, 192, 384, 512];
$baseDir = __DIR__;

// Цвета
$bgColor = [37, 99, 235]; // #2563eb
$textColor = [255, 255, 255];

echo "Генерация иконок PWA...\n\n";

foreach ($sizes as $size) {
    $filename = "icon-{$size}x{$size}.png";
    $filepath = $baseDir . '/' . $filename;
    
    // Создаем изображение
    $image = imagecreatetruecolor($size, $size);
    
    // Заливаем фон
    $bg = imagecolorallocate($image, $bgColor[0], $bgColor[1], $bgColor[2]);
    imagefill($image, 0, 0, $bg);
    
    // Цвет для текста и форм
    $white = imagecolorallocate($image, $textColor[0], $textColor[1], $textColor[2]);
    
    // Рисуем закругленные углы (имитация)
    $cornerRadius = (int)($size * 0.15);
    
    // Рисуем букву "О" (круг)
    $circleX = (int)($size * 0.35);
    $circleY = (int)($size * 0.5);
    $circleRadius = (int)($size * 0.15);
    imagesetthickness($image, max(3, (int)($size * 0.04)));
    
    // Внешний круг
    for ($i = 0; $i < 360; $i += 5) {
        $x1 = $circleX + $circleRadius * cos(deg2rad($i));
        $y1 = $circleY + $circleRadius * sin(deg2rad($i));
        $x2 = $circleX + $circleRadius * cos(deg2rad($i + 5));
        $y2 = $circleY + $circleRadius * sin(deg2rad($i + 5));
        imageline($image, (int)$x1, (int)$y1, (int)$x2, (int)$y2, $white);
    }
    
    // Рисуем знак "+"
    $plusX = (int)($size * 0.65);
    $plusY = (int)($size * 0.5);
    $plusSize = (int)($size * 0.2);
    
    // Вертикальная линия
    imageline(
        $image, 
        $plusX, 
        $plusY - $plusSize / 2, 
        $plusX, 
        $plusY + $plusSize / 2, 
        $white
    );
    
    // Горизонтальная линия
    imageline(
        $image, 
        $plusX - $plusSize / 2, 
        $plusY, 
        $plusX + $plusSize / 2, 
        $plusY, 
        $white
    );
    
    // Сохраняем
    imagepng($image, $filepath);
    imagedestroy($image);
    
    echo "✓ Создан: {$filename}\n";
}

echo "\n✅ Готово! Все иконки созданы.\n";
echo "\nФайлы сохранены в: {$baseDir}\n";
