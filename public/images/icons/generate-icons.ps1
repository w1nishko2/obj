# PowerShell скрипт для генерации иконок PWA из SVG
# Требует установленного ImageMagick

$sizes = @(72, 96, 128, 144, 152, 192, 384, 512)
$source = "icon.svg"

# Проверка наличия исходного файла
if (-not (Test-Path $source)) {
    Write-Error "Файл $source не найден"
    exit 1
}

# Проверка наличия ImageMagick
$convertPath = Get-Command convert -ErrorAction SilentlyContinue

if (-not $convertPath) {
    Write-Error "ImageMagick не установлен"
    Write-Host "Скачайте и установите ImageMagick с https://imagemagick.org/script/download.php"
    Write-Host "После установки перезапустите PowerShell"
    exit 1
}

Write-Host "Генерация иконок PWA..."

foreach ($size in $sizes) {
    $output = "icon-${size}x${size}.png"
    Write-Host "Создание $output..."
    
    & convert -background none -resize "${size}x${size}" $source $output
    
    if ($LASTEXITCODE -ne 0) {
        Write-Error "Ошибка при создании $output"
    }
}

Write-Host "Готово! Все иконки созданы." -ForegroundColor Green
