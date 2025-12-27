<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Инвестиционное предложение - Объект+</title>
    <meta name="description" content="Инвестируйте в платформу для управления строительными проектами. Готовый продукт, первые клиенты, нужно 2-3 млн на маркетинг.">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <style>
        :root {
            --color-primary: #6f6f6f;
            --color-primary-light: #8f8f8f;
            --color-success: #6ba97f;
            --color-info: #6ba9d4;
            --color-dark: #1a1a1a;
            --color-gray: #4a4a4a;
            --color-light: #f5f5f5;
            --color-white: #ffffff;
            --border-radius: 12px;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: var(--color-dark);
            background: var(--color-light);
            font-size: 15px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: var(--color-primary);
            color: white;
            padding: 40px 20px;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }
        
        header h1 {
            font-size: 36px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        header p {
            font-size: 16px;
            opacity: 0.95;
            font-weight: 400;
        }
        
        .content {
            background: var(--color-white);
            border-radius: var(--border-radius);
            padding: 30px 20px;
            margin: -20px auto 20px;
            max-width: 1000px;
            box-shadow: var(--shadow-lg);
        }
        
        h2 {
            color: var(--color-dark);
            font-size: 24px;
            margin-top: 30px;
            margin-bottom: 15px;
            border-left: 5px solid var(--color-primary);
            padding-left: 15px;
            font-weight: 700;
        }
        
        h3 {
            color: var(--color-gray);
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        .highlight-box {
            background: #f8f9fa;
            border-left: 5px solid var(--color-primary);
            padding: 20px 15px;
            margin: 20px 0;
            border-radius: var(--border-radius);
            font-size: 15px;
        }
        
        .success-box {
            background: #f0f8f4;
            border-left: 5px solid var(--color-success);
            padding: 20px 15px;
            margin: 20px 0;
            border-radius: var(--border-radius);
        }
        
        .metric-box {
            background: var(--color-primary);
            color: white;
            padding: 25px 15px;
            border-radius: var(--border-radius);
            margin: 20px 0;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }
        
        .metric-value {
            font-size: 36px;
            font-weight: 700;
            margin: 10px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        th, td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid #e8e8e8;
            white-space: nowrap;
        }
        
        th {
            background-color: var(--color-primary);
            color: white;
            font-weight: 600;
            font-size: 13px;
        }
        
        tr:hover {
            background-color: var(--color-light);
        }
        
        tbody tr:last-c24px;
            font-weight: 700;
            color: var(--color-primary);
        }
        
        ul {
            line-height: 1.8;
            font-size: 15px;
            margin-left: 20px;
            padding-left: 5px;
        }
        
        li {
            margin-bottom: 8
            margin-left: 25px;
        }
        
        li {
            margin-bottom: 10px;
        }
        
        strong {
            color: var(--color-dark);
            font-weight: 600;
        }
        
        .contact-box {
            background: var(--color-dark);
            color: wh30px 20px;
            border-radius: var(--border-radius);
            margin: 30px 0;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }
        
        .contact-box h2 {
            color: white;
            border: none;
            padding: 0;
            margin-bottom: 15px;
            font-size: 22px;
        }
        
        .btn-contact {
            display: inline-block;
            background: var(--color-success);
            color: white;
            padding: 12px 30px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            margin-top: 1500;
            margin-top: 20px;
            transition: all 0.3s;
            box-shadow: var(--shadow);
        }
        
        .btn-contact:hover {
            background: #5a9170;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }
        
        footer {20px;
            color: var(--color-gray);
            font-size: 13px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            header {
                padding: 30px 15px;
            }
            
            header h1 {
                font-size: 24px;
            }
            
            header p {
                font-size: 14px;
            }
            
            .content {
                padding: 20px 15px;
                margin: -15px 10px 15px;
            }
            
            h2 {
                font-size: 20px;
                margin-top: 25px;
                margin-bottom: 12px;
            }
            
            h3 {
                font-size: 16px;
                margin-top: 15px;
            }
            
            .highlight-box,
            .success-box {
                padding: 15px 12px;
                margin: 15px 0;
                font-size: 14px;
            }
            
            .metric-box {
                padding: 20px 15px;
                margin: 15px 0;
            }
            
            .metric-value {
                font-size: 28px;
            }
            
            table {
                font-size: 12px;
                margin: 15px 0;
            }
            
            th, td {
                padding: 8px 6px;
                font-size: 12px;
            }
            
            th {
                font-size: 11px;
            }
            
            ul {
                font-size: 14px;
                margin-left: 15px;
                line-height: 1.6;
            }
            
            li {
                margin-bottom: 6px;
            }
            
            .big-number {
                font-size: 20px;
            }
            
            .contact-box {
                padding: 25px 15px;
            }
            
            .contact-box h2 {
                font-size: 18px;
            }
            
            .contact-box p {
                font-size: 14px;
            }
            
            .btn-contact {
                padding: 10px 25px;
                font-size: 14px;
            }
        }
        
        @media (max-width: 480px) {
            header h1 {
                font-size: 20px;
            }
            
            header p {
                font-size: 13px;
            }
            
            h2 {
                font-size: 18px;
            }
            
            h3 {
                font-size: 15px;
            }
            
            .content {
                padding: 15px 10px;
            }
            
            .metric-value {
                font-size: 24px;
            }
            
            table {
                font-size: 11px;
            }
            
            th, td {
                padding: 6px 4px;
            }
            
            ul {
                font-size: 13
            .metric-value {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
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
                            <ul style="margin: 5px 0; padding-left: 20px;">
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
                            <ul style="margin: 5px 0; padding-left: 20px;">
                                <li>Мощный хостинг/сервер</li>
                                <li>Ускорение работы системы</li>
                                <li>Готовность к большой нагрузке</li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>

            <h2>📈 ЧТО ПОЛУЧИТ ИНВЕСТОР</h2>

            <h3>Сейчас в проекте:</h3>
            <ul>
                <li>✅ <strong>Продукт работает</strong> - можете зайти и протестировать прямо сейчас</li>
                <li>✅ <strong>Есть платящие клиенты</strong> - тарифы от 50 до 18,000 руб</li>
                <li>✅ <strong>Технологии надежные</strong> - Laravel, Vue.js, все современное</li>
                <li>✅ <strong>Интеграции готовы</strong> - онлайн-оплата через ЮKassa работает</li>
                <li>✅ <strong>Вложено уже ~1 млн</strong> собственных средств в разработку</li>
            </ul>

            <h3>Финансовый план (консервативный прогноз):</h3>

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

            <div class="success-box">
                <h3 style="margin-top: 0;">💡 Почему эти цифры реальны:</h3>
                <ul>
                    <li><strong>Средний чек:</strong> 2,000 руб/мес - прораб на одном проекте зарабатывает эту сумму за пару часов</li>
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
                <table style="border: none;">
                    <tr style="background: none;">
                        <td style="border: none; width: 50%;"><strong>1. Продукт готов</strong><br>Не идея на салфетке - работающая система с клиентами</td>
                        <td style="border: none;"><strong>2. Знаем рынок</strong><br>Сами из строительства, понимаем боли прорабов</td>
                    </tr>
                    <tr style="background: none;">
                        <td style="border: none;"><strong>3. Низкая цена</strong><br>2,000 руб/мес - прораб экономит это за день работы</td>
                        <td style="border: none;"><strong>4. Быстрый старт</strong><br>С рекламой начнем получать клиентов через неделю</td>
                    </tr>
                    <tr style="background: none;">
                        <td style="border: none;"><strong>5. Масштабируемость</strong><br>Один раз разработали - продаем бесконечно</td>
                        <td style="border: none;"><strong>6. Повторные продажи</strong><br>Подписка ежемесячная - стабильный доход</td>
                    </tr>
                </table>
            </div>

            <h2>⚡ РИСКИ (честно)</h2>

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

           
        </div>
    </div>

    <footer>
        <p><strong>Дата:</strong> Декабрь 2025 | <strong>Проект:</strong> Объект+ | <strong>Статус:</strong> Готов к масштабированию</p>
    </footer>
</body>
</html>
