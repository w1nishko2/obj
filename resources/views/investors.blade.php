<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Инвестиционное предложение - Объект+</title>
    <meta name="description" content="Инвестируйте в платформу для управления строительными проектами. Готовый продукт, первые клиенты, нужно 2-3 млн на маркетинг.">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=106059483', 'ym');

    ym(106059483, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/106059483" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --primary: #6f6f6f;
            --primary-dark: #4a4a4a;
            --secondary: #6ba97f;
            --text: #0a0a0a;
            --text-light: #4a4a4a;
            --bg: #ffffff;
            --bg-light: #f8f9fa;
            --border: #e0e0e0;
        }
        
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            color: var(--text);
            background: var(--bg-light);
            line-height: 1.6;
            font-size: 15px;
        }
        
        .nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            z-index: 100;
        }
        
        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .logo {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }
        
        .nav-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .nav-link {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.2s;
        }
        
        .nav-link:hover { color: var(--primary); }
        
        .btn-nav {
            background: var(--primary);
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .btn-nav:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }
        
        .hero {
            margin-top: 60px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .hero h1 {
            font-size: 38px;
            font-weight: 800;
            margin-bottom: 12px;
            line-height: 1.2;
        }
        
        .hero p {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 0px;
        }
        
        .main {
            background: white;
            margin: -30px auto 30px;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            padding: 40px 30px;
        }
        
        .section {
            margin-bottom: 40px;
        }
        
        .section:last-child { margin-bottom: 0; }
        
        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--text);
            border-left: 4px solid var(--primary);
            padding-left: 15px;
        }
        
        .section-subtitle {
            font-size: 18px;
            font-weight: 600;
            margin: 20px 0 12px;
            color: var(--text-light);
        }
        
        .card {
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
        }
        
        .card-highlight {
            background: linear-gradient(135deg, #f8f9fa, #fff);
            border-left: 4px solid var(--primary);
        }
        
        .card-success {
            background: linear-gradient(135deg, #f0f8f4, #fff);
            border-left: 4px solid var(--secondary);
        }
        
        .card-metric {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            text-align: center;
            padding: 30px 20px;
        }
        
        .metric-label {
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 8px;
        }
        
        .metric-value {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -1px;
        }
        
        .grid {
            display: grid;
            gap: 20px;
        }
        
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
        
        .table-wrap {
            overflow-x: auto;
            margin: 15px 0;
            border-radius: 8px;
            border: 1px solid var(--border);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 600px;
        }
        
        th {
            background: var(--primary);
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid var(--border);
        }
        
        tbody tr:hover { background: var(--bg-light); }
        tbody tr:last-child td { border-bottom: none; }
        
        ul, ol {
            margin-left: 20px;
            line-height: 1.8;
        }
        
        li { margin-bottom: 6px; }
        
        strong {
            font-weight: 600;
            color: var(--text);
        }
        
        .feature-item {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px;
            transition: all 0.2s;
        }
        
        .feature-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        
        .feature-title {
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 6px;
            font-size: 15px;
        }
        
        .feature-desc {
            color: var(--text-light);
            font-size: 14px;
            line-height: 1.5;
        }
        
        .cta-box {
            background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
            color: white;
            text-align: center;
            padding: 40px 30px;
            border-radius: 12px;
            margin: 30px 0;
        }
        
        .cta-box h2 {
            font-size: 26px;
            margin-bottom: 12px;
        }
        
        .cta-box p {
            opacity: 0.9;
            margin-bottom: 20px;
        }
        
        .btn-cta {
            display: inline-block;
            background: var(--secondary);
            color: white;
            padding: 14px 36px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s;
        }
        
        .btn-cta:hover {
            background: #5a9170;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(107,169,127,0.3);
        }
        
        footer {
            background: #1a1a1a;
            color: white;
            padding: 30px 20px;
            margin-top: 40px;
        }
        
        .footer-inner {
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .footer-link {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }
        
        .footer-link:hover { color: white; }
        
        .footer-text {
            color: rgba(255,255,255,0.5);
            font-size: 13px;
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .nav-right .nav-link { display: none; }
            .hero { padding: 50px 20px; }
            .hero h1 { font-size: 28px; }
            .hero p { font-size: 16px; }
            .main { padding: 30px 20px; margin: -25px 15px 25px; }
            .section-title { font-size: 22px; }
            .section-subtitle { font-size: 17px; }
            .metric-value { font-size: 36px; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            table { font-size: 13px; }
            th, td { padding: 10px 8px; }
        }
        
        @media (max-width: 480px) {
            .hero h1 { font-size: 24px; }
            .section-title { font-size: 20px; }
            .main { padding: 25px 15px; }
            .card { padding: 16px; }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="nav">
        <div class="nav-inner">
            <a href="{{ url('/') }}" class="logo">💼 Объект+</a>
            <div class="nav-right">
                <a href="{{ url('/') }}" class="nav-link">Главная</a>
                <a href="{{ url('/#features') }}" class="nav-link">Возможности</a>
                @auth
                    <a href="{{ route('home') }}" class="btn-nav">Мои проекты</a>
                @else
                    <a href="{{ route('register') }}" class="btn-nav">Начать</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <div class="hero">
        <div class="hero-content">
            <h1>💼 Инвестиционное предложение</h1>
            <p>Объект+ | Платформа для управления строительными проектами</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="main">
            <!-- Суть проекта -->
            <div class="section">
                <div class="card card-highlight">
                    <h2 class="section-title" style="margin-bottom: 15px; border: none; padding: 0;">🎯 Суть проекта простыми словами</h2>
                    <p><strong>"Объект+"</strong> - это приложение для прорабов и строительных компаний. Помогает управлять ремонтами: задачи, сроки, деньги, команда - все в одном месте. Вместо Excel, блокнотов и 100 звонков в день.</p>
                    <p style="margin-top: 10px;"><strong>Продукт готов</strong>, есть платящие клиенты. Нужны деньги на маркетинг, чтобы рассказать о нас большему количеству людей.</p>
                </div>
            </div>

            <!-- Зачем нужны инвестиции -->
            <div class="section">
                <h2 class="section-title">💰 Зачем нужны инвестиции</h2>
                
                <div class="card card-metric">
                    <div class="metric-label">Требуется инвестиций</div>
                    <div class="metric-value">2-3 млн ₽</div>
                </div>

                <h3 class="section-subtitle">Куда пойдут деньги:</h3>
                <div class="table-wrap">
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
                                <td>Директолог, таргетолог, рекламные бюджеты на 6 месяцев</td>
                            </tr>
                            <tr>
                                <td><strong>Улучшение инфраструктуры</strong></td>
                                <td>500,000 ₽</td>
                                <td>Мощный хостинг, ускорение системы, готовность к нагрузке</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Что получит инвестор -->
            <div class="section">
                <h2 class="section-title">📈 Что получит инвестор</h2>
                
                <h3 class="section-subtitle">Сейчас в проекте:</h3>
                <ul>
                    <li>✅ <strong>Продукт работает</strong> - можете зайти и протестировать прямо сейчас</li>
                    <li>✅ <strong>Есть платящие клиенты</strong> - тарифы от 490₽ до 29,900₽</li>
                    <li>✅ <strong>Технологии надежные</strong> - Laravel, Vue.js, все современное</li>
                    <li>✅ <strong>Интеграции готовы</strong> - онлайн-оплата через ЮKassa работает</li>
                    <li>✅ <strong>Вложено уже ~1 млн</strong> собственных средств в разработку</li>
                </ul>

                <h3 class="section-subtitle">Финансовый план (консервативный прогноз):</h3>
                <div class="table-wrap">
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
                                <td><strong>Сейчас</strong></td>
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

                <div class="card card-success">
                    <h3 class="section-subtitle" style="margin-top: 0;">💡 Почему эти цифры реальны:</h3>
                    <ul style="margin-top: 10px;">
                        <li><strong>Средний чек:</strong> 1,290 руб/мес - прораб зарабатывает эту сумму за пару часов</li>
                        <li><strong>Конверсия:</strong> из 100 регистраций ~10-15 начнут платить</li>
                        <li><strong>Рост постепенный:</strong> по 10-20 новых платящих клиентов в месяц</li>
                        <li><strong>Рынок есть:</strong> только в Москве и МО работают ~5,000 прорабов</li>
                        <li><strong>Конкуренция низкая:</strong> нет аналогов именно для строительства в РФ</li>
                    </ul>
                </div>
            </div>

            <!-- Окупаемость -->
            <div class="section">
                <h2 class="section-title">💵 Окупаемость для инвестора</h2>
                
                <div class="grid grid-2">
                    <div class="card card-highlight">
                        <h3 class="section-subtitle" style="margin-top: 0;">Вариант 1: Доля в бизнесе</h3>
                        <p><strong>Инвестиция:</strong> 2-3 млн ₽</p>
                        <p><strong>Доля:</strong> 15-20%</p>
                        <p><strong>Через 2 года при выручке 6-10 млн/год:</strong></p>
                        <ul style="margin-top: 10px;">
                            <li>Чистая прибыль ~50-60% = 3-6 млн ₽/год</li>
                            <li>Ваша доля (20%) = <strong>600 тыс - 1.2 млн ₽/год</strong></li>
                            <li><strong>Окупаемость: 24-30 месяцев</strong></li>
                        </ul>
                        <p style="margin-top: 10px;"><strong>ROI через 3 года: 2-3x</strong> от вложений</p>
                    </div>

                    <div class="card card-highlight">
                        <h3 class="section-subtitle" style="margin-top: 0;">Вариант 2: Заем под процент</h3>
                        <p><strong>Сумма займа:</strong> 2-3 млн ₽</p>
                        <p><strong>Срок:</strong> 24-30 месяцев</p>
                        <p><strong>Процент:</strong> 25-30% годовых</p>
                        <p><strong>Возврат:</strong> ежемесячно начиная с 6-7 месяца</p>
                        <p style="margin-top: 10px;"><strong>Итого получит:</strong> 3.2-4.5 млн ₽</p>
                    </div>
                </div>
            </div>

            <!-- Почему мы победим -->
            <div class="section">
                <h2 class="section-title">🎯 Почему мы победим</h2>
                
                <div class="grid grid-3">
                    <div class="feature-item">
                        <div class="feature-title">1. Продукт готов</div>
                        <div class="feature-desc">Не идея на салфетке - работающая система с клиентами</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-title">2. Знаем рынок</div>
                        <div class="feature-desc">Сами из строительства, понимаем боли прорабов</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-title">3. Гибкая ценовая политика</div>
                        <div class="feature-desc">От 490₽/мес до 2,990₽/мес для разных сегментов</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-title">4. Быстрый старт</div>
                        <div class="feature-desc">С рекламой начнем получать клиентов через неделю</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-title">5. Масштабируемость</div>
                        <div class="feature-desc">Один раз разработали - продаем бесконечно</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-title">6. Повторные продажи</div>
                        <div class="feature-desc">Подписка ежемесячная - стабильный доход</div>
                    </div>
                </div>
            </div>

            <!-- Риски -->
            <div class="section">
                <h2 class="section-title">⚡ Риски (честно)</h2>
                
                <div class="table-wrap">
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
                                <td>Тестируем 3-4 канала параллельно</td>
                            </tr>
                            <tr>
                                <td>Клиенты не будут платить</td>
                                <td>Низкая</td>
                                <td>Уже есть платящие клиенты</td>
                            </tr>
                            <tr>
                                <td>Технические проблемы</td>
                                <td>Средняя</td>
                                <td>Улучшаем хостинг, есть поддержка</td>
                            </tr>
                            <tr>
                                <td>Появится конкурент</td>
                                <td>Средняя</td>
                                <td>Быстро растем, захватываем рынок</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Итого -->
            <div class="section">
                <h2 class="section-title">🎤 Итого: простыми словами</h2>
                
                <div class="card card-success" style="font-size: 16px; line-height: 1.8;">
                    <p><strong>Что есть:</strong> Готовый продукт, первые клиенты, работающая модель</p>
                    <p><strong>Что нужно:</strong> 2-3 млн рублей на маркетинг и инфраструктуру</p>
                    <p><strong>Что получит инвестор:</strong></p>
                    <ul style="margin: 10px 0;">
                        <li>Либо долю 15-20% с доходом 600к-1.2 млн ₽/год через 2 года</li>
                        <li>Либо возврат 3.2-4.5 млн ₽ через 24-30 месяцев</li>
                    </ul>
                    <p><strong>Окупаемость:</strong> 24-30 месяцев (консервативно)</p>
                    <p><strong>Риски:</strong> Умеренные, рост постепенный, без авантюр</p>
                </div>
            </div>

            <!-- CTA -->
            <div class="cta-box">
                <h2>📞 Заинтересовались?</h2>
                <p>Свяжитесь со мной для обсуждения деталей</p>
                <a href="https://t.me/WooowItReally" class="btn-cta" target="_blank">Написать в Telegram</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-inner">
            <div class="footer-links">
                <a href="{{ url('/') }}" class="footer-link">Главная</a>
                <a href="{{ url('/#features') }}" class="footer-link">Возможности</a>
                <a href="{{ url('/#pricing') }}" class="footer-link">Тарифы</a>
                <a href="https://t.me/WooowItReally" class="footer-link" target="_blank">Telegram</a>
            </div>
            <div class="footer-text">
                <p><strong>Дата:</strong> Декабрь 2025 | <strong>Проект:</strong> Объект+ | <strong>Статус:</strong> Готов к масштабированию</p>
                <p style="margin-top: 8px;">&copy; 2025 Объект+. Все права защищены.</p>
            </div>
        </div>
    </footer>
</body>
</html>
