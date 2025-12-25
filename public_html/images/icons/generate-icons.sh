#!/bin/bash

# Скрипт для генерации иконок PWA из SVG файла
# Требует установленного ImageMagick или Inkscape

# Размеры иконок
SIZES=(72 96 128 144 152 192 384 512)

# Исходный SVG файл
SOURCE="icon.svg"

# Проверка наличия исходного файла
if [ ! -f "$SOURCE" ]; then
    echo "Ошибка: файл $SOURCE не найден"
    exit 1
fi

# Создаем иконки всех размеров
echo "Генерация иконок PWA..."

for size in "${SIZES[@]}"
do
    OUTPUT="icon-${size}x${size}.png"
    echo "Создание $OUTPUT..."
    
    # Используем ImageMagick (если установлен)
    if command -v convert &> /dev/null; then
        convert -background none -resize ${size}x${size} "$SOURCE" "$OUTPUT"
    # Или Inkscape (если установлен)
    elif command -v inkscape &> /dev/null; then
        inkscape -w $size -h $size "$SOURCE" -o "$OUTPUT"
    # Или rsvg-convert (если установлен)
    elif command -v rsvg-convert &> /dev/null; then
        rsvg-convert -w $size -h $size "$SOURCE" -o "$OUTPUT"
    else
        echo "Ошибка: не найден инструмент для конвертации (ImageMagick, Inkscape или librsvg)"
        echo "Установите один из них:"
        echo "  - Ubuntu/Debian: sudo apt-get install imagemagick"
        echo "  - MacOS: brew install imagemagick"
        echo "  - Windows: скачайте с https://imagemagick.org/script/download.php"
        exit 1
    fi
done

echo "Готово! Иконки созданы."
