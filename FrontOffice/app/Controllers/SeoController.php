<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ArticleModel;
use Core\Controller;

class SeoController extends Controller
{
    public function robots(): void
    {
        header('Content-Type: text/plain; charset=UTF-8');

        $appUrl = rtrim((string) ($this->config['app_url'] ?? ''), '/');
        if ($appUrl === '') {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
            $appUrl = $scheme . '://' . $host;
        }

        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /BackOffice\n";
        echo "Disallow: /uploads/\n\n";
        echo 'Sitemap: ' . $appUrl . "/sitemap.xml\n";
    }

    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=UTF-8');

        $appUrl = rtrim((string) ($this->config['app_url'] ?? ''), '/');
        if ($appUrl === '') {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
            $appUrl = $scheme . '://' . $host;
        }

        $model = new ArticleModel($this->config);
        $articles = $model->getSitemapArticles();

        $urls = [
            [
                'loc' => $appUrl . '/articles',
                'lastmod' => date('c'),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
        ];

        foreach ($articles as $article) {
            $slug = trim((string) ($article['slug'] ?? ''));
            if ($slug === '') {
                continue;
            }

            $lastmodSource = (string) (
                $article['date_publication']
                ?? $article['date_creation']
                ?? ''
            );

            $lastmod = strtotime($lastmodSource);

            $urls[] = [
                'loc' => $appUrl . '/articles/' . rawurlencode($slug),
                'lastmod' => $lastmod !== false ? date('c', $lastmod) : date('c'),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        echo "\n";

        foreach ($urls as $url) {
            echo "  <url>\n";
            echo '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
            echo '    <lastmod>' . htmlspecialchars($url['lastmod'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</lastmod>\n";
            echo '    <changefreq>' . htmlspecialchars($url['changefreq'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</changefreq>\n";
            echo '    <priority>' . htmlspecialchars($url['priority'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</priority>\n";
            echo "  </url>\n";
        }

        echo '</urlset>';
    }
}
