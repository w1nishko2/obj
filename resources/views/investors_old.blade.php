<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Инвестиционное предложение - Объект+</title>
    <meta name="description" content="Инвестируйте в платформу для управления строительными проектами. Готовый продукт, первые клиенты, нужно 2-3 млн на маркетинг.">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Colors - соответствуют стилю проекта */
            --primary-color: #6f6f6f;
            --primary-dark: #4a4a4a;
            --primary-light: #8f8f8f;
            --secondary-color: #6ba97f;
            --accent-color: #6ba9d4;
            --success-color: #6ba97f;
            --warning-color: #e8a66b;
            --danger-color: #d97979;
            
            /* Neutrals */
            --text-primary: #0a0a0a;
            --text-secondary: #4a4a4a;
            --text-muted: #8a8a8a;
            --bg-white: #ffffff;
            --bg-light: #f8f9fa;
            --bg-dark: #1a1a1a;
            --border-color: #e0e0e0;
            
            /* Spacing */
            --container-max-width: 1200px;
            --section-padding: 80px;
            
            /* Typography */
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            
            /* Shadows */
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
            
            /* Border Radius */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            
            /* Transitions */
            --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-family);
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--bg-light);
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Navigation */
        .landing-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-sm);
            z-index: 1000;
            transition: var(--transition-base);
        }
        
        .nav-container {
            max-width: var(--container-max-width);
            margin: 0 auto;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav-logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }
        
        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition-base);
        }
        
        .nav-link:hover {
            color: var(--primary-color);
        }
        
        .nav-cta {
            background: var(--primary-color);
            color: white;
            padding: 10px 24px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition-base);
        }
        
        .nav-cta:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        /* Header */
        header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 100px 24px 50px;
            text-align: center;
            box-shadow: var(--shadow-lg);
            margin-top: 64px;
        }
        
        header h1 {
            font-size: 36px;
            margin-bottom: 12px;
            font-weight: 700;
            line-height: 1.2;
        }
        
        header p {
            font-size: 18px;
            opacity: 0.95;
            font-weight: 400;
        }
        
        .container {
            max-width: var(--container-max-width);
            margin: 0 auto;
            padding: 0 24px;
        }
        
        .content {
            background: var(--bg-white);
            border-radius: var(--radius-lg);
            padding: 40px 32px;
            margin: -30px auto 30px;
            max-width: 1000px;
            box-shadow: var(--shadow-lg);
        }
        
        h2 {
            color: var(--text-primary);
            font-size: 26px;
            margin-top: 32px;
            margin-bottom: 16px;
            border-left: 5px solid var(--primary-color);
            padding-left: 16px;
            font-weight: 700;
            line-height: 1.3;
        }
        
        h2:first-child {
            margin-top: 0;
        }
        
        h3 {
            color: var(--text-secondary);
            font-size: 20px;
            margin-top: 24px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border: 2px solid var(--border-color);
            border-left: 5px solid var(--primary-color);
            padding: 20px 20px;
            margin: 20px 0;
            border-radius: var(--radius-md);
            font-size: 15px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition-base);
        }
        
        .highlight-box:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        
        .success-box {
            background: linear-gradient(135deg, #f0f8f4 0%, #e6f5ed 100%);
            border: 2px solid var(--secondary-color);
            border-left: 5px solid var(--secondary-color);
            padding: 20px 20px;
            margin: 20px 0;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: var(--transition-base);
        }
        
        .success-box:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        
        .metric-box {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 30px 20px;
            border-radius: var(--radius-md);
            margin: 20px 0;
            text-align: center;
            box-shadow: var(--shadow-lg);
            transition: var(--transition-base);
        }
        
        .metric-box:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg), 0 0 0 1px var(--primary-color);
        }
        
        .metric-value {
            font-size: 42px;
            font-weight: 700;
            margin: 12px 0;
            letter-spacing: -1px;
        }
        
        /* Таблицы - адаптивные */
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 20px 0;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
            min-width: 600px;
        }
        
        th, td {
            padding: 12px 12px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
        }
        
        tbody tr {
            background: white;
            transition: var(--transition-base);
        }
        
        tbody tr:hover {
            background: var(--bg-light);
        }
        
        tbody tr:last-child td {
            border-bottom: none;
        }
        
        .big-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        ul {
            line-height: 1.6;
            font-size: 15px;
            margin-left: 20px;
            padding-left: 0;
        }
        
        ol {
            line-height: 1.6;
            font-size: 15px;
            margin-left: 20px;
        }
        
        li {
            margin-bottom: 8px;
        }
        
        li::marker {
            color: var(--primary-color);
        }
        
        strong {
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .contact-box {
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--text-secondary) 100%);
            color: white;
            padding: 32px 24px;
            border-radius: var(--radius-lg);
            margin: 32px 0;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }
        
        .contact-box h2 {
            color: white;
            border: none;
            padding: 0;
            margin-bottom: 12px;
            font-size: 24px;
        }
        
        .contact-box p {
            font-size: 16px;
            margin-bottom: 16px;
            opacity: 0.95;
        }
        
        .btn-contact {
            display: inline-block;
            background: var(--secondary-color);
            color: white;
            padding: 16px 40px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            margin-top: 8px;
            transition: var(--transition-base);
            box-shadow: var(--shadow-md);
        }
        
        .btn-contact:hover {
            background: #5a9170;
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            color: white;
        }
        
        /* Footer */
        footer {
            background: var(--bg-dark);
            color: white;
            padding: 32px 24px;
            text-align: center;
            margin-top: 40px;
        }
        
        .footer-content {
            max-width: var(--container-max-width);
            margin: 0 auto;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 32px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        
        .footer-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition-base);
        }
        
        .footer-link:hover {
            color: white;
        }
        
        .footer-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            margin-top: 24px;
        }
        
        /* Tablet */
        @media (max-width: 1024px) {
            header {
                padding: 90px 24px 50px;
            }
            
            header h1 {
                font-size: 32px;
            }
            
            .content {
                padding: 32px 24px;
            }
            
            h2 {
                font-size: 24px;
            }
        }
        
        /* Mobile Landscape / Small Tablet */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            header {
                padding: 80px 20px 40px;
            }
            
            header h1 {
                font-size: 28px;
            }
            
            header p {
                font-size: 15px;
            }
            
            .content {
                padding: 24px 16px;
                margin: -25px 16px 24px;
            }
            
            h2 {
                font-size: 22px;
                margin-top: 24px;
                margin-bottom: 16px;
                padding-left: 12px;
            }
            
            h3 {
                font-size: 18px;
                margin-top: 20px;
                margin-bottom: 10px;
            }
            
            .highlight-box,
            .success-box {
                padding: 16px 16px;
                margin: 16px 0;
                font-size: 14px;
            }
            
            .metric-box {
                padding: 24px 16px;
                margin: 16px 0;
            }
            
            .metric-value {
                font-size: 36px;
            }
            
            .table-wrapper {
                margin: 24px -20px;
                border-radius: 0;
            }
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 12px 10px;
                font-size: 13px;
            }
            
            ul, ol {
                font-size: 15px;
                margin-left: 20px;
            }
            
            li {
                margin-bottom: 10px;
            }
            
            .big-number {
                font-size: 24px;
            }
            
            .contact-box {
                padding: 36px 20px;
                margin: 36px 0;
            }
            
            .contact-box h2 {
                font-size: 24px;
            }
            
            .contact-box p {
                font-size: 16px;
            }
            
            .btn-contact {
                padding: 14px 32px;
                font-size: 16px;
                display: block;
                max-width: 100%;
            }
            
            .footer-links {
                gap: 16px;
            }
        }
        
        /* Mobile Portrait */
        @media (max-width: 480px) {
            .nav-logo {
                font-size: 20px;
            }
            
            header {
                padding: 70px 16px 35px;
            }
            
            header h1 {
                font-size: 24px;
            }
            
            header p {
                font-size: 14px;
            }
            
            h2 {
                font-size: 20px;
                margin-top: 20px;
                padding-left: 10px;
            }
            
            h3 {
                font-size: 16px;
            }
            
            .content {
                padding: 20px 16px;
                margin: -20px 12px 20px;
            }
            
            .highlight-box,
            .success-box {
                padding: 16px 14px;
            }
            
            .metric-box {
                padding: 20px 16px;
            }
            
            .metric-value {
                font-size: 32px;
            }
            
            table {
                font-size: 13px;
            }
            
            th, td {
                padding: 10px 8px;
                font-size: 12px;
            }
            
            ul, ol {
                font-size: 14px;
                margin-left: 16px;
            }
            
            li {
                margin-bottom: 8px;
            }
            
            .big-number {
                font-size: 22px;
            }
            
            .contact-box {
                padding: 32px 16px;
            }
            
            .contact-box h2 {
                font-size: 22px;
            }
            
            .contact-box p {
                font-size: 15px;
            }
            
            .btn-contact {
                padding: 12px 28px;
                font-size: 15px;
            }
            
            footer {
                padding: 36px 16px;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="landing-nav">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="nav-logo">
                💼 Объект+
            </a>
            <div class="nav-links">
                <a href="{{ url('/') }}" class="nav-link">Главная</a>
                <a href="{{ url('/#features') }}" class="nav-link">Возможности</a>
                <a href="{{ url('/#pricing') }}" class="nav-link">Тарифы</a>
                @auth
                    <a href="{{ route('home') }}" class="nav-cta">Мои проекты</a>
                @else
                    <a href="{{ route('register') }}" class="nav-cta">Начать бесплатно</a>
                @endauth
            </div>
        </div>
    </nav>
    
    <!-- Header -->
    <header>
        <h1>💼 ИНВЕСТИЦИОННОЕ ПРЕДЛОЖЕНИЕ</h1>
        <p>Объект+ | Платформа для управления строительными проектами</p>
    </header>
    
    <div class="container">
        <div class="content">
            <div class="highlight-box">
                <h2 style="margin-top: 0; border: none; padding: 0;">🎯 Суть проекта простыми словами</h2>
                <p><strong>"Объект+"</strong> - это приложение для прорабов и строительных компаний. Помогает управлять ремонтами: задачи, сроки, деньги, команда - все в одном месте. Вместо Excel, блокнотов и 100 звонков в день.</p>
                <p><strong>Продукт готов</strong>, есть платящие клиенты. Нужны деньги на маркетинг, чтобы рассказать о нас большему количеству людей.</p>
            </div>

            <h2>💰 ЗАЧЕМ НУЖНЫ ИНВЕСТИЦИИ</h2>
            
            <div class="metric-box">
                <h3 style="color: white; margin: 0;">Требуется инвестиций</h3>
                <div class="metric-value">2-3 млн ₽</div>
            </div>

            <h3>Куда пойдут деньги:</h3>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Направление</th>
                            <th>Сумма</th>
                            <th>Для чего</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Отдел маркетинга</strong></td>
                            <td>1.5-2 млн ₽</td>
                            <td>
                                <ul style="margin: 8px 0; padding-left: 24px;">
                                    <li>Директолог (Яндекс.Директ, Google Ads)</li>
                                    <li>Таргетолог (ВКонтакте, Telegram)</li>
                                    <li>Рекламные бюджеты на 6 месяцев</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Улучшение инфраструктуры</strong></td>
                            <td>500,000 ₽</td>
                            <td>
                                <ul style="margin: 8px 0; padding-left: 24px;">
                                    <li>Мощный хостинг/сервер</li>
                                    <li>Ускорение работы системы</li>
                                    <li>Готовность к большой нагрузке</li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h2>📈 ЧТО ПОЛУЧИТ ИНВЕСТОР</h2>

            <h3>Сейчас в проекте:</h3>
            <ul>
                <li>✅ <strong>Продукт работает</strong> - можете зайти и протестировать прямо сейчас</li>
                <li>✅ <strong>Есть платящие клиенты</strong> - тарифы от 490₽ до 29,900₽</li>
                <li>✅ <strong>Технологии надежные</strong> - Laravel, Vue.js, все современное</li>
                <li>✅ <strong>Интеграции готовы</strong> - онлайн-оплата через ЮKassa работает</li>
                <li>✅ <strong>Вложено уже ~1 млн</strong> собственных средств в разработку</li>
            </ul>

            <h3>Финансовый план (консервативный прогноз):</h3>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Период</th>
                            <th>Платящих клиентов</th>
                            <th>Выручка/месяц</th>
                            <th>Выручка/год</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Сейчас (до инвестиций)</strong></td>
                            <td>5-10</td>
                            <td>10-20 тыс ₽</td>
                            <td>~150 тыс ₽</td>
                        </tr>
                        <tr style="background: #fff3cd;">
                            <td><strong>Через 6 месяцев</strong></td>
                            <td>40-60</td>
                            <td>80-120 тыс ₽</td>
                            <td>~1 млн ₽</td>
                        </tr>
                        <tr style="background: #d4edda;">
                            <td><strong>Через 12 месяцев</strong></td>
                            <td>100-150</td>
                            <td>200-300 тыс ₽</td>
                            <td>2.4-3.6 млн ₽</td>
                        </tr>
                        <tr style="background: #d1ecf1;">
                            <td><strong>Через 24 месяца</strong></td>
                            <td>250-400</td>
                            <td>500-800 тыс ₽</td>
                            <td>6-10 млн ₽</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="success-box">
                <h3 style="margin-top: 0;">💡 Почему эти цифры реальны:</h3>
                <ul>
                    <li><strong>Средний чек:</strong> 1,290 руб/мес (профессиональный тариф) - прораб на одном проекте зарабатывает эту сумму за пару часов</li>
                    <li><strong>Тарифная сетка:</strong> 490₽ (стартовый), 1,290₽ (профессиональный), 2,990₽ (корпоративный) - для разных сегментов</li>
                    <li><strong>Конверсия:</strong> из 100 регистраций ~10-15 начнут платить (реальная статистика SaaS)</li>
                    <li><strong>Рост постепенный:</strong> по 10-20 новых платящих клиентов в месяц - это реально с рекламой</li>
                    <li><strong>Рынок есть:</strong> только в Москве и МО работают ~5,000 прорабов постоянно</li>
                    <li><strong>Конкуренция низкая:</strong> нет аналогов именно для строительства в РФ</li>
                </ul>
            </div>

            <h2>💵 ОКУПАЕМОСТЬ ДЛЯ ИНВЕСТОРА</h2>

            <h3>Вариант 1: Доля в бизнесе</h3>
            <div class="highlight-box">
                <p><strong>Инвестиция:</strong> <span class="big-number">2-3 млн ₽</span></p>
                <p><strong>Доля:</strong> <span class="big-number">15-20%</span></p>
                <p><strong>Через 2 года при выручке 6-10 млн/год:</strong></p>
                <ul>
                    <li>Чистая прибыль ~50-60% = 3-6 млн ₽/год (у SaaS высокая маржа)</li>
                    <li>Ваша доля (20%) = <strong class="big-number">600 тыс - 1.2 млн ₽/год пассивного дохода</strong></li>
                    <li><strong>Окупаемость: 24-30 месяцев</strong></li>
                </ul>
                <p><strong>ROI через 3 года:</strong> <span class="big-number">2-3x</span> от вложений (консервативно)</p>
            </div>

            <h3>Вариант 2: Заем под процент</h3>
            <div class="highlight-box">
                <p><strong>Сумма займа:</strong> 2-3 млн ₽</p>
                <p><strong>Срок:</strong> 24-30 месяцев</p>
                <p><strong>Процент:</strong> 25-30% годовых</p>
                <p><strong>Возврат:</strong> ежемесячно начиная с 6-7 месяца (когда выручка стабильна)</p>
                <p><strong>Итого инвестор получит:</strong> <span class="big-number">3.2-4.5 млн ₽</span></p>
                <p style="font-size: 14px; margin-top: 15px; color: #666;">*Возврат по мере роста бизнеса, без угрозы для операционки</p>
            </div>

            <h2>🎯 ПОЧЕМУ МЫ ПОБЕДИМ</h2>

            <div class="success-box">
                <h3 style="margin-top: 0;">Наши преимущества:</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-top: 24px;">
                    <div>
                        <strong style="display: block; margin-bottom: 8px; color: var(--secondary-color);">1. Продукт готов</strong>
                        <p style="margin: 0; color: var(--text-secondary);">Не идея на салфетке - работающая система с клиентами</p>
                    </div>
                    <div>
                        <strong style="display: block; margin-bottom: 8px; color: var(--secondary-color);">2. Знаем рынок</strong>
                        <p style="margin: 0; color: var(--text-secondary);">Сами из строительства, понимаем боли прорабов</p>
                    </div>
                    <div>
                        <strong style="display: block; margin-bottom: 8px; color: var(--secondary-color);">3. Гибкая ценовая политика</strong>
                        <p style="margin: 0; color: var(--text-secondary);">От 490₽/мес (стартовый) до 2,990₽/мес (корпоративный)</p>
                    </div>
                    <div>
                        <strong style="display: block; margin-bottom: 8px; color: var(--secondary-color);">4. Быстрый старт</strong>
                        <p style="margin: 0; color: var(--text-secondary);">С рекламой начнем получать клиентов через неделю</p>
                    </div>
                    <div>
                        <strong style="display: block; margin-bottom: 8px; color: var(--secondary-color);">5. Масштабируемость</strong>
                        <p style="margin: 0; color: var(--text-secondary);">Один раз разработали - продаем бесконечно</p>
                    </div>
                    <div>
                        <strong style="display: block; margin-bottom: 8px; color: var(--secondary-color);">6. Повторные продажи</strong>
                        <p style="margin: 0; color: var(--text-secondary);">Подписка ежемесячная - стабильный доход</p>
                    </div>
                </div>
            </div>

            <h2>⚡ РИСКИ (честно)</h2>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Риск</th>
                            <th>Вероятность</th>
                            <th>Что делаем</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Реклама не сработает</td>
                            <td>Низкая</td>
                            <td>Тестируем 3-4 канала параллельно, хотя бы один сработает</td>
                        </tr>
                        <tr>
                            <td>Клиенты не будут платить</td>
                            <td>Низкая</td>
                            <td>Уже есть платящие клиенты, модель работает</td>
                        </tr>
                        <tr>
                            <td>Технические проблемы</td>
                            <td>Средняя</td>
                            <td>Улучшаем хостинг, есть техническая поддержка</td>
                        </tr>
                        <tr>
                            <td>Появится конкурент</td>
                            <td>Средняя</td>
                            <td>Быстро растем, захватываем рынок первыми</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h2>🎤 ИТОГО: ПРОСТЫМИ СЛОВАМИ</h2>

            <div class="success-box" style="font-size: 18px; line-height: 2;">
                <p><strong>Что есть:</strong> Готовый продукт, первые клиенты, работающая модель</p>
                <p><strong>Что нужно:</strong> 2-3 млн рублей на маркетинг и инфраструктуру</p>
                <p><strong>Что получит инвестор:</strong></p>
                <ul>
                    <li>Либо долю 15-20% с пассивным доходом 600к-1.2 млн ₽/год через 2 года</li>
                    <li>Либо возврат 3.2-4.5 млн ₽ через 24-30 месяцев (заем под 25-30%)</li>
                </ul>
                <p><strong>Окупаемость:</strong> 24-30 месяцев (консервативно)</p>
                <p><strong>Риски:</strong> Умеренные, рост постепенный, без авантюр</p>
                <p style="font-size: 16px; color: #666; margin-top: 15px;"><em>Прогноз консервативный - реальные результаты могут быть лучше при удачном маркетинге</em></p>
            </div>

            <div class="contact-box">
                <h2>📞 ЗАИНТЕРЕСОВАЛИСЬ?</h2>
                <p>Свяжитесь со мной для обсуждения деталей</p>
                <a href="https://t.me/WooowItReally" class="btn-contact" target="_blank">Написать в Telegram</a>
            </div>
           
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="{{ url('/') }}" class="footer-link">Главная</a>
                <a href="{{ url('/#features') }}" class="footer-link">Возможности</a>
                <a href="{{ url('/#pricing') }}" class="footer-link">Тарифы</a>
                <a href="https://t.me/WooowItReally" class="footer-link" target="_blank">Telegram</a>
                <a href="mailto:support@objectplus.ru" class="footer-link">Контакты</a>
            </div>
            <div class="footer-text">
                <p style="margin-bottom: 8px;"><strong>Дата:</strong> Декабрь 2025 | <strong>Проект:</strong> Объект+ | <strong>Статус:</strong> Готов к масштабированию</p>
                <p>&copy; 2025 Объект+. Все права защищены.</p>
            </div>
        </div>
    </footer>
</body>
</html>
