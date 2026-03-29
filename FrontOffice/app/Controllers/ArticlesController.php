<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ArticleModel;
use Core\Controller;

class ArticlesController extends Controller
{
    private const PER_PAGE = 6;

    private const DEFAULT_META_DESCRIPTION = 'Analyses politiques, geopolitiques et actualites sur l\'Iran, avec dossiers de fond et articles verifies.';

    public function index(): void
    {
        $model = new ArticleModel($this->config);

        $search = trim((string) ($_GET['q'] ?? ''));
        $categoryId = max(0, (int) ($_GET['category'] ?? 0));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $hasFilter = $search !== '' || $categoryId > 0;

        $categories = $model->getCategories();

        $featuredArticle = null;
        if (!$hasFilter) {
            $featuredArticle = $model->getFeaturedArticle();
        }

        $excludeId = $featuredArticle !== null ? (int) $featuredArticle['id_article'] : null;

        $total = $model->countPublishedArticles($search, $categoryId, $excludeId);
        $totalPages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min($page, $totalPages);

        $articles = $model->getPublishedArticles($search, $categoryId, $excludeId, $page, self::PER_PAGE);

        $activeCategory = null;
        foreach ($categories as $category) {
            if ((int) $category['id_article_categorie'] === $categoryId) {
                $activeCategory = $category;
                break;
            }
        }

        $metaTitle = 'Actualites Iran et analyses geopolitques | Iran Focus';
        if ($activeCategory !== null) {
            $metaTitle = 'Categorie ' . (string) ($activeCategory['libelle'] ?? 'Articles') . ' | Iran Focus';
        }

        $metaDescription = self::DEFAULT_META_DESCRIPTION;
        if ($search !== '') {
            $metaDescription = 'Resultats pour "' . $search . '" dans les analyses et actualites sur l\'Iran.';
        } elseif ($activeCategory !== null) {
            $metaDescription = 'Selection d\'articles de la categorie ' . (string) ($activeCategory['libelle'] ?? 'Iran') . ' sur Iran Focus.';
        }

        $metaDescription = $this->normalizeMetaDescription($metaDescription);

        $canonicalPath = '/articles';
        $query = [];
        if ($search !== '') {
            $query['q'] = $search;
        }
        if ($categoryId > 0) {
            $query['category'] = $categoryId;
        }
        if ($page > 1) {
            $query['page'] = $page;
        }
        if ($query !== []) {
            $canonicalPath .= '?' . http_build_query($query);
        }

        $structuredData = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => 'Iran Focus',
                'url' => rtrim((string) ($this->config['app_url'] ?? ''), '/') . '/articles',
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => rtrim((string) ($this->config['app_url'] ?? ''), '/') . '/articles?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Articles Iran Focus',
                'description' => $metaDescription,
            ],
        ];

        $this->view('articles/index', [
            'title' => $metaTitle,
            'metaDescription' => $metaDescription,
            'canonicalPath' => $canonicalPath,
            'structuredData' => $structuredData,
            'categories' => $categories,
            'search' => $search,
            'activeCategory' => $activeCategory,
            'categoryId' => $categoryId,
            'featuredArticle' => $featuredArticle,
            'articles' => $articles,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalArticles' => $total,
        ]);
    }

    public function show(string $slug = ''): void
    {
        if ($slug === '') {
            $this->renderNotFound();
            return;
        }

        $model = new ArticleModel($this->config);
        $article = $model->getPublishedArticleBySlug($slug);

        if ($article === null) {
            $this->renderNotFound();
            return;
        }

        $images = $model->getActiveImagesByArticleId((int) $article['id_article']);
        $categories = $model->getCategories();

        $pageTitle = trim((string) ($article['meta_title'] ?? ''));
        if ($pageTitle === '') {
            $pageTitle = (string) $article['titre'];
        }

        $metaDescription = trim((string) ($article['meta_description'] ?? ''));
        if ($metaDescription === '') {
            $metaDescription = strip_tags((string) ($article['contenu'] ?? ''));
        }

        $metaDescription = $this->normalizeMetaDescription($metaDescription);

        $canonicalPath = '/articles/' . rawurlencode((string) $article['slug']);

        $structuredData = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'NewsArticle',
                'headline' => (string) ($article['titre'] ?? ''),
                'description' => $metaDescription,
                'datePublished' => !empty($article['date_publication']) ? date('c', strtotime((string) $article['date_publication'])) : null,
                'dateModified' => !empty($article['date_modification']) ? date('c', strtotime((string) $article['date_modification'])) : null,
                'author' => [
                    '@type' => 'Person',
                    'name' => (string) ($article['author_name'] ?? 'Editorial Team'),
                ],
                'articleSection' => (string) ($article['category_label'] ?? ''),
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => rtrim((string) ($this->config['app_url'] ?? ''), '/') . $canonicalPath,
                ],
                'image' => [
                    (string) ($article['image_principale'] ?? ''),
                ],
            ],
        ];
        $structuredData = array_map(static function (array $entry): array {
            return array_filter($entry, static fn ($value): bool => $value !== null && $value !== '');
        }, $structuredData);

        $this->view('articles/show', [
            'title' => $pageTitle . ' | Iran Focus',
            'metaDescription' => $metaDescription,
            'canonicalPath' => $canonicalPath,
            'structuredData' => $structuredData,
            'article' => $article,
            'images' => $images,
            'categories' => $categories,
            'search' => '',
            'categoryId' => (int) $article['id_article_categorie'],
        ]);
    }

    private function renderNotFound(): void
    {
        http_response_code(404);
        $this->view('errors/404', [
            'title' => 'Article introuvable | Iran Focus',
            'metaDescription' => 'La page demandee est introuvable. Consultez les derniers articles d\'Iran Focus.',
            'robotsMeta' => 'noindex,follow',
            'canonicalPath' => '/articles',
            'categories' => [],
            'search' => '',
            'categoryId' => 0,
        ]);
    }

    private function normalizeMetaDescription(string $text): string
    {
        $clean = trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');

        if ($clean === '') {
            return self::DEFAULT_META_DESCRIPTION;
        }

        if (strlen($clean) > 160) {
            return rtrim(substr($clean, 0, 157)) . '...';
        }

        return $clean;
    }
}
