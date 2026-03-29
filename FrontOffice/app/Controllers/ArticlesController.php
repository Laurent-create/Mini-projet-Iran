<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ArticleModel;
use Core\Controller;

class ArticlesController extends Controller
{
    private const PER_PAGE = 6;

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

        $this->view('articles/index', [
            'title' => 'Articles | Iran Focus',
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

        $this->view('articles/show', [
            'title' => $pageTitle . ' | Iran Focus',
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
            'categories' => [],
            'search' => '',
            'categoryId' => 0,
        ]);
    }
}
