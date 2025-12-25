<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Генерация динамического sitemap.xml
     */
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
        $sitemap .= ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
        $sitemap .= ' xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9';
        $sitemap .= ' http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        // Главная страница (Лендинг)
        $sitemap .= $this->addUrl(url('/'), '2025-12-22', 'weekly', '1.0');

        // Страницы аутентификации
        $sitemap .= $this->addUrl(route('register'), '2025-12-22', 'monthly', '0.9');
        $sitemap .= $this->addUrl(route('login'), '2025-12-22', 'monthly', '0.8');

        // Разделы лендинга
        $sitemap .= $this->addUrl(url('/#features'), '2025-12-22', 'monthly', '0.7');
        $sitemap .= $this->addUrl(url('/#benefits'), '2025-12-22', 'monthly', '0.7');
        $sitemap .= $this->addUrl(url('/#pricing'), '2025-12-22', 'weekly', '0.8');
        $sitemap .= $this->addUrl(url('/#contact'), '2025-12-22', 'monthly', '0.6');

        $sitemap .= '</urlset>';

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Добавить URL в sitemap
     */
    private function addUrl($loc, $lastmod, $changefreq, $priority)
    {
        $url = '<url>';
        $url .= '<loc>' . htmlspecialchars($loc) . '</loc>';
        $url .= '<lastmod>' . $lastmod . '</lastmod>';
        $url .= '<changefreq>' . $changefreq . '</changefreq>';
        $url .= '<priority>' . $priority . '</priority>';
        $url .= '</url>';
        
        return $url;
    }
}
