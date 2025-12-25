@php
$iconMap = [
    // Общие
    'arrow-left' => 'arrow-left',
    'arrow-right' => 'arrow-right',
    'arrow-counterclockwise' => 'rotate-ccw',
    'plus' => 'plus',
    'plus-lg' => 'plus',
    'plus-circle' => 'plus-circle',
    'x' => 'x',
    'x-lg' => 'x',
    'x-circle' => 'x-circle',
    'check' => 'check',
    'check-circle' => 'check-circle',
    'check2-circle' => 'check-circle',
    'check2-square' => 'check-square',
    'archive' => 'archive',
    
    // Навигация
    'home' => 'home',
    'building' => 'building',
    'folder2-open' => 'folder',
    'list-check' => 'check-square',
    
    // Люди и контакты
    'people' => 'users',
    'person' => 'user',
    'person-circle' => 'user',
    'telephone' => 'phone',
    
    // Документы
    'file-earmark-text' => 'file-text',
    'file-earmark-spreadsheet' => 'file',
    'file-earmark-check' => 'file-check',
    'file-pdf' => 'file',
    'file-excel' => 'file',
    'receipt' => 'file-text',
    'cash-coin' => 'dollar-sign',
    
    // Медиа
    'images' => 'image',
    'camera' => 'camera',
    'box-seam' => 'package',
    
    // Действия
    'pencil' => 'edit-2',
    'trash' => 'trash-2',
    'download' => 'download',
    'upload' => 'upload',
    'send' => 'send',
    
    // Интерфейс
    'calendar3' => 'calendar',
    'geo-alt' => 'map-pin',
    'info-circle' => 'info',
    'exclamation-triangle' => 'alert-triangle',
    'chat' => 'message-circle',
    'chat-dots' => 'message-circle',
    'currency-exchange' => 'dollar-sign',
    
    // Прочее
    'chevron-down' => 'chevron-down',
    'chevron-up' => 'chevron-up',
    'three-dots-vertical' => 'more-vertical',
];

$featherIcon = $iconMap[$icon] ?? $icon;
@endphp
<i data-feather="{{ $featherIcon }}" {{ $attributes }}></i>
