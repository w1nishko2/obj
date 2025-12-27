<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Код подтверждения email</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 30px;
            border: 1px solid #e0e0e0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .code-box {
            background-color: #ffffff;
            border: 2px solid #a70000;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #a70000;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }
        .info {
            color: #666;
            font-size: 14px;
            text-align: center;
            margin-top: 15px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #a70000; margin: 0;">Подтверждение email</h1>
        </div>
        
        <p>Здравствуйте!</p>
        
        <p>Вы запросили подтверждение email адреса. Используйте код ниже для завершения процесса:</p>
        
        <div class="code-box">
            <div class="code">{{ $verificationCode }}</div>
        </div>
        
        <div class="info">
            <p><strong>⏰ Код действителен в течение {{ $expiresInMinutes }} минут</strong></p>
            <p>Если вы не запрашивали этот код, просто проигнорируйте это письмо.</p>
        </div>
        
        <div class="footer">
            <p>Это автоматическое письмо, не отвечайте на него.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Все права защищены.</p>
        </div>
    </div>
</body>
</html>
