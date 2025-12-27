<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- SEO Meta Tags 2025 -->
    <title>Система управления строительными проектами | CRM для прорабов и строительных компаний</title>
    <meta name="description" content="Профессиональная система управления строительством: контроль проектов, сметы, документы, команда в одном месте. Увеличьте прибыль на 30%. От 50 рублей в месяц.">
    <meta name="keywords" content="управление строительством, crm для строителей, система для прорабов, строительные проекты, сметы онлайн, контроль строительства, автоматизация строительства, управление прорабами, строительная crm, учет строительных объектов">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <meta name="bingbot" content="index, follow">
    <meta name="yandex" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Geo Tags -->
    <meta name="geo.region" content="RU">
    <meta name="geo.placename" content="Россия">
    <meta name="language" content="Russian">
    
    <!-- Additional SEO -->
    <meta name="rating" content="general">
    <meta name="distribution" content="global">
    <meta name="revisit-after" content="7 days">
    <meta name="classification" content="Business, Software">
    <meta name="category" content="Construction Management Software">
    <meta name="coverage" content="Worldwide">
    <meta name="target" content="all">
    <meta name="audience" content="all">
    <link rel="alternate" hreflang="ru" href="{{ url('/') }}">
    
    <!-- Mobile Optimization -->
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Система управления строительные проекты | CRM для прорабов">
    <meta property="og:description" content="Автоматизируйте управление строительством. Контроль проектов, сметы, документы в одной системе. Увеличьте прибыль на 30%.">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Система управления строительными проектами">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="fb:app_id" content="">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@work">
    <meta name="twitter:creator" content="@work">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="Система управления строительными проектами">
    <meta name="twitter:description" content="Автоматизируйте управление строительством. Контроль проектов, сметы, документы в одной системе.">
    <meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}">
    <meta name="twitter:image:alt" content="Система управления строительными проектами">
    
    <!-- Structured Data (JSON-LD) - Расширенная микроразметка -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "@id": "{{ url('/') }}#organization",
                "name": "{{ config('app.name') }}",
                "url": "{{ url('/') }}",
                "logo": {
                    "@type": "ImageObject",
                    "url": "{{ asset('images/icons/icon-512x512.png') }}",
                    "width": 512,
                    "height": 512
                },
                "sameAs": [
                    "https://t.me/WooowItReally",
                    "https://vk.com/work",
                    "https://youtube.com/@work"
                ],
                "contactPoint": {
                    "@type": "ContactPoint",
                    "telephone": "+7-904-448-22-83",
                    "contactType": "customer service",
                    "availableLanguage": ["Russian"],
                    "areaServed": "RU"
                }
            },
            {
                "@type": "WebSite",
                "@id": "{{ url('/') }}#website",
                "url": "{{ url('/') }}",
                "name": "Система управления строительными проектами",
                "description": "Профессиональная CRM для прорабов и строительных компаний",
                "publisher": {
                    "@id": "{{ url('/') }}#organization"
                },
                "inLanguage": "ru-RU"
            },
            {
                "@type": "SoftwareApplication",
                "@id": "{{ url('/') }}#software",
                "name": "{{ config('app.name') }}",
                "applicationCategory": "BusinessApplication",
                "applicationSubCategory": "Construction Management",
                "operatingSystem": "Web, iOS, Android, Windows, macOS",
                "offers": {
                    "@type": "AggregateOffer",
                    "priceCurrency": "RUB",
                    "lowPrice": "50",
                    "highPrice": "2990",
                    "offerCount": "3",
                    "priceSpecification": [
                        {
                            "@type": "UnitPriceSpecification",
                            "price": "50",
                            "priceCurrency": "RUB",
                            "name": "Базовый",
                            "referenceQuantity": {
                                "@type": "QuantitativeValue",
                                "value": "1",
                                "unitCode": "MON"
                            }
                        },
                        {
                            "@type": "UnitPriceSpecification",
                            "price": "990",
                            "priceCurrency": "RUB",
                            "name": "Профессиональный",
                            "referenceQuantity": {
                                "@type": "QuantitativeValue",
                                "value": "1",
                                "unitCode": "MON"
                            }
                        },
                        {
                            "@type": "UnitPriceSpecification",
                            "price": "2990",
                            "priceCurrency": "RUB",
                            "name": "Бизнес",
                            "referenceQuantity": {
                                "@type": "QuantitativeValue",
                                "value": "1",
                                "unitCode": "MON"
                            }
                        }
                    ]
                },
                "description": "Профессиональная система управления строительными проектами: сметы, документы, контроль команды",
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "4.8",
                    "reviewCount": "127",
                    "bestRating": "5",
                    "worstRating": "1"
                },
                "featureList": "Управление проектами, Автоматические сметы, Документы и акты, Управление командой, Фото-отчеты, Аналитика",
                "screenshot": "{{ asset('images/og-image.jpg') }}"
            },
            {
                "@type": "WebPage",
                "@id": "{{ url('/') }}#webpage",
                "url": "{{ url('/') }}",
                "name": "Система управления строительными проектами",
                "isPartOf": {
                    "@id": "{{ url('/') }}#website"
                },
                "about": {
                    "@id": "{{ url('/') }}#software"
                },
                "primaryImageOfPage": {
                    "@type": "ImageObject",
                    "url": "{{ asset('images/og-image.jpg') }}"
                },
                "datePublished": "2025-12-22",
                "dateModified": "2025-12-22",
                "description": "Профессиональная система управления строительством",
                "breadcrumb": {
                    "@type": "BreadcrumbList",
                    "itemListElement": [
                        {
                            "@type": "ListItem",
                            "position": 1,
                            "name": "Главная",
                            "item": "{{ url('/') }}"
                        }
                    ]
                }
            },
            {
                "@type": "FAQPage",
                "mainEntity": [
                    {
                        "@type": "Question",
                        "name": "Сколько стоит система?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Базовый тариф стоит всего 50 рублей в месяц. Для компаний есть расширенные тарифы от 990 рублей в месяц."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "Нужно ли устанавливать программу?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Нет, это облачная система. Работает прямо в браузере на компьютере, планшете или телефоне."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "Безопасны ли мои данные?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Да, мы используем шифрование данных, регулярные бэкапы и защищенные сервера."
                        }
                    }
                ]
            }
        ]
    }
    </script>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/images/icons/icon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/icons/icon.svg">
    <link rel="mask-icon" href="/images/icons/icon.svg" color="#6f6f6f">
    
    <!-- Web App Manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#6f6f6f">
    <meta name="msapplication-TileColor" content="#6f6f6f">
    <meta name="msapplication-config" content="/browserconfig.xml">
    
    <!-- Sitemap & Robots -->
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
    <link type="text/plain" rel="author" href="/humans.txt">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('css/landing.css') }}" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" as="style">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css"></noscript>
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="landing-nav">
        <div class="container">
            <div class="nav-content">
                <div class="logo">
                    <i class="bi bi-building"></i>
                    <span>{{ config('app.name') }}</span>
                </div>
                <div class="nav-links">
                    <a href="#features">Возможности</a>
                    <a href="#benefits">Преимущества</a>
                    <a href="#pricing">Тарифы</a>
                    <a href="#contact">Контакты</a>
                    @auth
                        <a href="{{ route('home') }}" class="btn-primary">Личный кабинет</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline">Войти</a>
                        <a href="{{ route('register') }}" class="btn-primary">Попробовать</a>
                    @endauth
                </div>
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-gradient"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        Управляйте строительством
                        <span class="gradient-text">профессионально</span>
                    </h1>
                    <p class="hero-description">
                        Все инструменты для эффективного управления строительными проектами в одной платформе. 
                        Контролируйте сроки, бюджет и команду. Увеличьте прибыль на 30%.
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('register') }}" class="btn-hero-primary">
                            Начать работу
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Активных прорабов</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">2000+</div>
                            <div class="stat-label">Завершенных проектов</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">30%</div>
                            <div class="stat-label">Рост эффективности</div>
                        </div>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="mockup-wrapper">
                        <div class="mockup-card">
                            <i class="bi bi-clipboard-check"></i>
                            <div>
                                <div class="mockup-title">Проект завершен</div>
                                <div class="mockup-subtitle">Коттедж, ул. Садовая</div>
                            </div>
                        </div>
                        <div class="mockup-card">
                            <i class="bi bi-graph-up"></i>
                            <div>
                                <div class="mockup-title">+23% к прибыли</div>
                                <div class="mockup-subtitle">В этом месяце</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem Section -->
    <section class="problem-section">
        <div class="container">
            <div class="section-header">
                <h2>Знакомые проблемы?</h2>
                <p>Большинство прорабов и строительных компаний сталкиваются с этими сложностями</p>
            </div>
            <div class="problems-grid">
                <div class="problem-card">
                    <div class="problem-icon">😰</div>
                    <h3>Хаос в документах</h3>
                    <p>Сметы в Excel, акты в Word, фото в телефоне. Важные файлы теряются в критический момент.</p>
                </div>
                <div class="problem-card">
                    <div class="problem-icon">⏰</div>
                    <h3>Срыв сроков</h3>
                    <p>Сложно отследить прогресс по всем объектам. Клиенты недовольны задержками.</p>
                </div>
                <div class="problem-card">
                    <div class="problem-icon">💸</div>
                    <h3>Перерасход бюджета</h3>
                    <p>Нет контроля над расходами. Материалы покупаются с запасом "на всякий случай".</p>
                </div>
                <div class="problem-card">
                    <div class="problem-icon">🤦</div>
                    <h3>Проблемы с командой</h3>
                    <p>Бригады не знают задач, прораб тратит время на объяснения одного и того же.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Возможности</span>
                <h2>Все для управления проектами</h2>
                <p>Комплексное решение для строительного бизнеса любого масштаба</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-kanban"></i>
                    </div>
                    <h3>Управление проектами</h3>
                    <p>Создавайте проекты, делите на этапы, отслеживайте прогресс в реальном времени. Полный контроль над каждым объектом.</p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-circle"></i> Канбан-доски для задач</li>
                        <li><i class="bi bi-check-circle"></i> Календарь работ</li>
                        <li><i class="bi bi-check-circle"></i> Уведомления о дедлайнах</li>
                    </ul>
                </div>

                <div class="feature-card featured">
                    <div class="feature-badge">Популярное</div>
                    <div class="feature-icon">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <h3>Автоматические сметы</h3>
                    <p>Создавайте детальные сметы за минуты. Система автоматически рассчитывает стоимость работ и материалов.</p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-circle"></i> Шаблоны работ и материалов</li>
                        <li><i class="bi bi-check-circle"></i> Экспорт в Excel/PDF</li>
                        <li><i class="bi bi-check-circle"></i> История изменений</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h3>Документы и акты</h3>
                    <p>Генерируйте профессиональные документы одним кликом. Все акты и договоры всегда под рукой.</p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-circle"></i> Готовые шаблоны документов</li>
                        <li><i class="bi bi-check-circle"></i> Электронная подпись</li>
                        <li><i class="bi bi-check-circle"></i> Облачное хранилище</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3>Команда и клиенты</h3>
                    <p>Добавляйте прорабов, мастеров, клиентов. Каждый видит только свои задачи и может общаться в чатах.</p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-circle"></i> Роли и права доступа</li>
                        <li><i class="bi bi-check-circle"></i> Встроенный чат</li>
                        <li><i class="bi bi-check-circle"></i> Клиентский портал</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-camera"></i>
                    </div>
                    <h3>Фото-отчеты</h3>
                    <p>Загружайте фото с объектов, группируйте по этапам. Клиенты всегда в курсе прогресса работ.</p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-circle"></i> Фото до/после</li>
                        <li><i class="bi bi-check-circle"></i> Привязка к этапам</li>
                        <li><i class="bi bi-check-circle"></i> Автоматические галереи</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h3>Аналитика и отчеты</h3>
                    <p>Понимайте финансовое состояние бизнеса. Принимайте решения на основе данных, а не догадок.</p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-circle"></i> Прибыль по проектам</li>
                        <li><i class="bi bi-check-circle"></i> Контроль расходов</li>
                        <li><i class="bi bi-check-circle"></i> Прогноз выручки</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="benefits-section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Преимущества</span>
                <h2>Почему выбирают нас</h2>
            </div>
            
            <div class="benefits-grid">
                <div class="benefit-item">
                    <div class="benefit-number">01</div>
                    <div class="benefit-content">
                        <h3>Экономия 15+ часов в неделю</h3>
                        <p>Автоматизация рутинных задач освобождает время для важных дел. Меньше бумажной работы - больше времени на объектах.</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-number">02</div>
                    <div class="benefit-content">
                        <h3>Рост прибыли на 30%</h3>
                        <p>Контроль бюджета, сокращение перерасхода материалов, оптимизация работы команды приводят к значительному росту прибыли.</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-number">03</div>
                    <div class="benefit-content">
                        <h3>Довольные клиенты</h3>
                        <p>Прозрачность процессов, своевременная отчетность, соблюдение сроков - клиенты видят профессионализм и рекомендуют вас.</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-number">04</div>
                    <div class="benefit-content">
                        <h3>Работа из любой точки</h3>
                        <p>Облачная система доступна с телефона, планшета, компьютера. Управляйте проектами где угодно - на объекте, дома, в дороге.</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-number">05</div>
                    <div class="benefit-content">
                        <h3>Простота использования</h3>
                        <p>Интуитивный интерфейс, подробные инструкции, поддержка 24/7. Начните работать сразу без длительного обучения.</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-number">06</div>
                    <div class="benefit-content">
                        <h3>Безопасность данных</h3>
                        <p>Регулярные бэкапы, шифрование, защита от потери данных. Ваша информация в безопасности даже при поломке устройств.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2>Как это работает</h2>
                <p>Начните работу за 3 простых шага</p>
            </div>
            
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <h3>Регистрация</h3>
                    <p>Создайте аккаунт за 30 секунд. Укажите email и придумайте пароль. Выберите подходящий тариф.</p>
                </div>
                
                <div class="step-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
                
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <i class="bi bi-folder-plus"></i>
                    </div>
                    <h3>Создайте проект</h3>
                    <p>Добавьте первый проект, разбейте на этапы, пригласите команду. Система подскажет что делать дальше.</p>
                </div>
                
                <div class="step-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
                
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <i class="bi bi-rocket-takeoff"></i>
                    </div>
                    <h3>Работайте эффективно</h3>
                    <p>Управляйте проектами, создавайте сметы, контролируйте прогресс. Увидите результат в первую же неделю.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="pricing-section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Тарифы</span>
                <h2>Выберите подходящий тариф</h2>
                <p>Доступные цены для бизнеса любого масштаба</p>
            </div>
            
            <div class="pricing-grid">
                @foreach($plans as $plan)
                <div class="pricing-card {{ $plan->slug === 'yearly' ? 'popular' : '' }}">
                    @if($plan->slug === 'yearly')
                    <div class="pricing-badge">Самая выгодная</div>
                    @endif
                    
                    <h3 class="pricing-name">{{ $plan->name }}</h3>
                    <div class="pricing-price">
                        @if($plan->slug === 'yearly')
                            <span class="price-amount">{{ number_format($plan->price / 12, 0, ',', ' ') }} ₽</span>
                            <span class="price-period">/месяц</span>
                            <div style="font-size: 14px; color: #6ba97f; margin-top: 4px;">
                                Оплата {{ number_format($plan->price, 0, ',', ' ') }} ₽/год
                            </div>
                        @else
                            <span class="price-amount">{{ number_format($plan->price, 0, ',', ' ') }} ₽</span>
                            <span class="price-period">/месяц</span>
                        @endif
                    </div>
                    <p class="pricing-description">{{ $plan->description }}</p>
                    
                    <ul class="pricing-features">
                        @if($plan->features)
                            @foreach($plan->features as $feature)
                                <li><i class="bi bi-check-circle-fill"></i> {{ $feature }}</li>
                            @endforeach
                        @endif
                    </ul>
                    
                    <a href="{{ route('register') }}" class="btn-pricing">
                        Выбрать {{ $plan->name }}
                    </a>
                </div>
                @endforeach
            </div>
            
            <div class="pricing-note">
                <i class="bi bi-shield-check"></i>
                <p>Безопасная оплата. Отмените подписку в любой момент без объяснений.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header">
                <h2>Отзывы клиентов</h2>
                <p>Что говорят о нас профессионалы</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testimonial-text">"Раньше тратил по 3 часа в день на координацию бригад и отчеты клиентам. Теперь все автоматизировано. Освободившееся время использую для поиска новых заказов."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">ИП</div>
                        <div>
                            <div class="author-name">Иван Петров</div>
                            <div class="author-position">Прораб, 5 лет опыта</div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testimonial-text">"У нас 8 объектов одновременно. Раньше был полный хаос с документами. Теперь все сметы, акты, фото в одном месте. Клиенты довольны прозрачностью."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">СК</div>
                        <div>
                            <div class="author-name">Михаил Сергеев</div>
                            <div class="author-position">Директор СК "Стройком"</div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testimonial-text">"Система окупилась за первый же месяц. Сократили перерасход материалов на 20%, начали контролировать сроки. Прибыль выросла, клиенты рекомендуют нас друзьям."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">АС</div>
                        <div>
                            <div class="author-name">Алексей Смирнов</div>
                            <div class="author-position">ИП, строительство домов</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2>Часто задаваемые вопросы</h2>
            </div>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Сколько стоит система?</h3>
                        <i class="bi bi-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>14 дней бесплатного тестирования. Далее: Стартовый тариф 490₽/мес (4 900₽/год), Профессиональный 1 290₽/мес (12 900₽/год), Корпоративный 2 990₽/мес (29 900₽/год). Годовая подписка экономит 2 платежа в год.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Нужно ли устанавливать программу?</h3>
                        <i class="bi bi-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Нет, это облачная система. Работает прямо в браузере на компьютере, планшете или телефоне. Также есть мобильные приложения для iOS и Android.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Сложно ли освоить систему?</h3>
                        <i class="bi bi-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Интерфейс интуитивно понятный. Большинство пользователей начинают работать в первый день. Есть видео-инструкции и поддержка 24/7.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Безопасны ли мои данные?</h3>
                        <i class="bi bi-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Да, мы используем шифрование данных, регулярные бэкапы и защищенные серверы. Ваши проекты, сметы и документы полностью защищены.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Можно ли перенести данные из Excel?</h3>
                        <i class="bi bi-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Да, система поддерживает импорт данных из Excel. Мы поможем с переносом ваших существующих проектов и смет.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Что если мне не подойдет?</h3>
                        <i class="bi bi-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Вы можете отменить подписку в любой момент. Деньги вернем без вопросов в течение первых 14 дней. Все ваши данные можно выгрузить.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact" class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Готовы начать?</h2>
                <p>Присоединяйтесь к 500+ профессионалам, которые уже автоматизировали управление проектами</p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn-cta-primary">
                        Начать работу
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <p class="cta-note">Доступные тарифы от 490 ₽/мес • 14 дней бесплатно • Безопасная оплата • Отмена в любой момент</p>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-wrapper">
                <div class="contact-info">
                    <h2>Свяжитесь с нами</h2>
                    <p>Остались вопросы? Напишите нам, и мы ответим в течение часа.</p>
                    
                    <div class="contact-methods">
                        <div class="contact-method">
                            <i class="bi bi-envelope"></i>
                            <div>
                                <div class="method-label">Email</div>
                                <div class="method-value">w1nishko@yandex.ru</div>
                            </div>
                        </div>
                        <div class="contact-method">
                            <i class="bi bi-phone"></i>
                            <div>
                                <div class="method-label">Телефон</div>
                                <div class="method-value">+7 (904) 448-22-83</div>
                            </div>
                        </div>
                        <div class="contact-method">
                            <i class="bi bi-telegram"></i>
                            <div>
                                <div class="method-label">Telegram</div>
                                <div class="method-value">@WooowItReally</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('landing.contact') }}" method="POST" class="contact-form">
                    @csrf
                    
                    @if(session('success'))
                    <div class="alert-success">
                        <i class="bi bi-check-circle"></i>
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    <div class="form-group">
                        <label for="name">Ваше имя</label>
                        <input type="text" id="name" name="name" required class="form-control">
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required class="form-control">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Телефон (необязательно)</label>
                        <input type="tel" id="phone" name="phone" class="form-control">
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Сообщение</label>
                        <textarea id="message" name="message" rows="4" required class="form-control"></textarea>
                        @error('message')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        Отправить сообщение
                        <i class="bi bi-send"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="bi bi-building"></i>
                        <span>{{ config('app.name') }}</span>
                    </div>
                    <p>Профессиональная система управления строительными проектами</p>
                    <div class="social-links">
                        <a href="https://t.me/WooowItReally"><i class="bi bi-telegram"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Продукт</h4>
                    <ul>
                        <li><a href="#features">Возможности</a></li>
                        <li><a href="#pricing">Тарифы</a></li>
                        <li><a href="{{ route('register') }}">Регистрация</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Компания</h4>
                    <ul>
                        <li><a href="#">О нас</a></li>
                        <li><a href="#">Блог</a></li>
                        <li><a href="#">Вакансии</a></li>
                        <li><a href="#contact">Контакты</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Поддержка</h4>
                    <ul>
                        <li><a href="#">Справка</a></li>
                        <li><a href="#">Документация</a></li>
                        <li><a href="#">API</a></li>
                        <li><a href="#">Статус</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Все права защищены.</p>
                <div class="footer-links">
                    <a href="{{ url('/privacy-policy') }}">Политика конфиденциальности</a>
                    <a href="{{ url('/terms-of-use') }}">Условия использования</a>
                    <a href="{{ url('/offer') }}">Публичная оферта</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-content">
            <button class="mobile-menu-close" id="mobileMenuClose">
                <i class="bi bi-x"></i>
            </button>
            <nav class="mobile-nav">
                <a href="#features">Возможности</a>
                <a href="#benefits">Преимущества</a>
                <a href="#pricing">Тарифы</a>
                <a href="#contact">Контакты</a>
                @auth
                    <a href="{{ route('home') }}" class="btn-mobile-primary">Личный кабинет</a>
                @else
                    <a href="{{ route('login') }}" class="btn-mobile-outline">Войти</a>
                    <a href="{{ route('register') }}" class="btn-mobile-primary">Попробовать</a>
                @endauth
            </nav>
        </div>
    </div>

    <!-- PWA Install Button -->
    <div id="pwa-install-container" style="display: none;">
        <button class="pwa-install-button" id="pwaInstallBtn">
            <i class="bi bi-download"></i>
            <span class="pwa-label">Установить</span>
        </button>
        <button class="pwa-close-button" id="pwaCloseBtn">
            <i class="bi bi-x"></i>
        </button>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile Menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuClose = document.getElementById('mobileMenuClose');
        
        mobileMenuBtn?.addEventListener('click', () => {
            mobileMenu.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        mobileMenuClose?.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // FAQ Accordion
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const item = question.parentElement;
                const isActive = item.classList.contains('active');
                
                // Close all items
                document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));
                
                // Open clicked item if it wasn't active
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });
        
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href !== '#demo') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        // Close mobile menu if open
                        mobileMenu.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                }
            });
        });
        
        // Navbar scroll effect
        let lastScroll = 0;
        const navbar = document.querySelector('.landing-nav');
        
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            lastScroll = currentScroll;
        });
        
        // PWA Install
        let deferredPrompt;
        const pwaContainer = document.getElementById('pwa-install-container');
        const pwaInstallBtn = document.getElementById('pwaInstallBtn');
        const pwaCloseBtn = document.getElementById('pwaCloseBtn');
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Показываем кнопку, если пользователь еще не закрыл её
            if (!localStorage.getItem('pwa-install-dismissed')) {
                pwaContainer.style.display = 'block';
            }
        });
        
        pwaInstallBtn?.addEventListener('click', async () => {
            if (!deferredPrompt) {
                return;
            }
            
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            
            if (outcome === 'accepted') {
                console.log('PWA установлен');
            }
            
            deferredPrompt = null;
            pwaContainer.style.display = 'none';
        });
        
        pwaCloseBtn?.addEventListener('click', () => {
            pwaContainer.style.display = 'none';
            localStorage.setItem('pwa-install-dismissed', 'true');
        });
        
        // Скрываем кнопку после установки
        window.addEventListener('appinstalled', () => {
            pwaContainer.style.display = 'none';
            localStorage.setItem('pwa-install-dismissed', 'true');
        });
    </script>
</body>
</html>
