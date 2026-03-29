<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ArticleRepository;
use App\Repositories\ArticleStatusRepository;
use App\Repositories\CategoryRepository;
use PDO;

final class ArticleController extends Controller
{
    private ArticleRepository $articles;
    private CategoryRepository $categories;
    private ArticleStatusRepository $statuses;

    public function __construct(private PDO $pdo)
    {
        $this->articles = new ArticleRepository($pdo);
        $this->categories = new CategoryRepository($pdo);
        $this->statuses = new ArticleStatusRepository($pdo);
    }

    /** @param array<string,mixed> $query */
    public function index(array $query): void
    {
        $this->requireAuth();

        $filters = [
            'q' => trim((string) ($query['q'] ?? '')),
            'category' => isset($query['category']) ? (int) $query['category'] : 0,
            'status' => isset($query['status']) ? (int) $query['status'] : 0,
            'author' => isset($query['author']) ? (int) $query['author'] : 0,
        ];

        $page = isset($query['page']) ? max(1, (int) $query['page']) : 1;

        $forceAuthorId = null;
        if (!$this->isAdmin()) {
            $u = $this->currentUser();
            $forceAuthorId = $u ? (int) $u['id'] : null;
        }

        $result = $this->articles->paginate($filters, $forceAuthorId, $page, 10);

        $this->render('articles/index', [
            'title' => 'Articles',
            'articles' => $result['items'],
            'pagination' => $result,
            'filters' => $filters,
            'categories' => $this->categories->all(),
            'statuses' => $this->statuses->all(),
            'authors' => $this->isAdmin() ? $this->articles->authors() : [],
            'isAdmin' => $this->isAdmin(),
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();

        $this->render('articles/create', [
            'title' => 'Nouvel article',
            'errors' => [],
            'old' => [],
            'categories' => $this->categories->all(),
        ]);
    }

    /** @param array<string,mixed> $post @param array<string,mixed> $files */
    public function store(array $post, array $files): void
    {
        $this->requireAuth();
        $this->verifyCsrf($post);

        [$errors, $data] = $this->validate($post);

        $imagePath = '';
        if (!empty($files['image_principale'])) {
            $imagePath = (string) ($this->saveUploadedImage((array) $files['image_principale'], 'articles') ?? '');
            if ($imagePath === '') {
                $errors[] = "L'image principale n'est pas valide (jpg/jpeg/png/webp, 5 Mo max).";
            }
        }

        if (!empty($errors)) {
            $this->render('articles/create', [
                'title' => 'Nouvel article',
                'errors' => $errors,
                'old' => $data,
                'categories' => $this->categories->all(),
            ]);
            return;
        }

        $u = $this->currentUser();
        $authorId = (int) ($u['id'] ?? 0);

        $titre = $data['titre'];
        $slug = $this->slugify($titre);
        $metaTitle = $this->limit((string) $titre, 150);
        $metaDescription = $this->computeMetaDescription($data['description'] ?? '', $data['contenu'] ?? '');
        $contenu = $this->ensureImagesHaveAlt((string) ($data['contenu'] ?? ''), $titre);

        $id = $this->articles->create([
            'titre' => $titre,
            'contenu' => $contenu,
            'slug' => $slug,
            'image_principale' => $imagePath,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'id_article_categorie' => (int) $data['id_article_categorie'],
            'id_article_statu' => 1,
            'id_utilisateur' => $authorId,
        ]);

        $this->flashStatus('Article créé (brouillon).');
        $this->redirect('/articles/edit?id=' . $id);
    }

    /** @param array<string,mixed> $query */
    public function edit(array $query): void
    {
        $this->requireAuth();

        $id = isset($query['id']) ? (int) $query['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo 'Bad Request';
            return;
        }

        $article = $this->articles->find($id);
        if ($article === null) {
            http_response_code(404);
            echo 'Not Found';
            return;
        }

        if (!$this->canAccessArticle($article)) {
            http_response_code(403);
            echo '403 - Forbidden';
            return;
        }

        $this->render('articles/edit', [
            'title' => 'Éditer article',
            'article' => $article,
            'errors' => [],
            'old' => [
                'titre' => (string) ($article['titre'] ?? ''),
                'id_article_categorie' => (string) ($article['id_article_categorie'] ?? ''),
                'description' => (string) ($article['meta_description'] ?? ''),
                'contenu' => (string) ($article['contenu'] ?? ''),
            ],
            'categories' => $this->categories->all(),
        ]);
    }

    /** @param array<string,mixed> $query @param array<string,mixed> $post @param array<string,mixed> $files */
    public function update(array $query, array $post, array $files): void
    {
        $this->requireAuth();
        $this->verifyCsrf($post);

        $id = isset($query['id']) ? (int) $query['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo 'Bad Request';
            return;
        }

        $article = $this->articles->find($id);
        if ($article === null) {
            http_response_code(404);
            echo 'Not Found';
            return;
        }

        if (!$this->canAccessArticle($article)) {
            http_response_code(403);
            echo '403 - Forbidden';
            return;
        }

        [$errors, $data] = $this->validate($post);

        $imagePath = (string) ($article['image_principale'] ?? '');
        if (!empty($files['image_principale']) && (int) (($files['image_principale']['error'] ?? UPLOAD_ERR_NO_FILE)) !== UPLOAD_ERR_NO_FILE) {
            $newPath = (string) ($this->saveUploadedImage((array) $files['image_principale'], 'articles') ?? '');
            if ($newPath === '') {
                $errors[] = "L'image principale n'est pas valide (jpg/jpeg/png/webp, 5 Mo max).";
            } else {
                $imagePath = $newPath;
            }
        }

        if (!empty($errors)) {
            $this->render('articles/edit', [
                'title' => 'Éditer article',
                'article' => $article,
                'errors' => $errors,
                'old' => $data,
                'categories' => $this->categories->all(),
            ]);
            return;
        }

        $titre = $data['titre'];
        $slug = $this->slugify($titre);
        $metaTitle = $this->limit((string) $titre, 150);
        $metaDescription = $this->computeMetaDescription($data['description'] ?? '', $data['contenu'] ?? '');
        $contenu = $this->ensureImagesHaveAlt((string) ($data['contenu'] ?? ''), $titre);

        $this->articles->update($id, [
            'titre' => $titre,
            'contenu' => $contenu,
            'slug' => $slug,
            'image_principale' => $imagePath,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'id_article_categorie' => (int) $data['id_article_categorie'],
        ]);

        $this->flashStatus('Article mis à jour.');
        $this->redirect('/articles/edit?id=' . $id);
    }

    /** @param array<string,mixed> $query @param array<string,mixed> $post */
    public function publish(array $query, array $post): void
    {
        $this->requireAuth();
        $this->verifyCsrf($post);

        $id = isset($query['id']) ? (int) $query['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo 'Bad Request';
            return;
        }

        $article = $this->articles->find($id);
        if ($article === null) {
            http_response_code(404);
            echo 'Not Found';
            return;
        }

        if (!$this->canAccessArticle($article)) {
            http_response_code(403);
            echo '403 - Forbidden';
            return;
        }

        $this->articles->publish($id);
        $this->flashStatus('Article publié.');
        $this->redirect('/articles/edit?id=' . $id);
    }

    /** @param array<string,mixed> $query @param array<string,mixed> $post */
    public function archive(array $query, array $post): void
    {
        $this->requireAuth();
        $this->verifyCsrf($post);

        $id = isset($query['id']) ? (int) $query['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo 'Bad Request';
            return;
        }

        $article = $this->articles->find($id);
        if ($article === null) {
            http_response_code(404);
            echo 'Not Found';
            return;
        }

        if (!$this->canAccessArticle($article)) {
            http_response_code(403);
            echo '403 - Forbidden';
            return;
        }

        $this->articles->archive($id);
        $this->flashStatus('Article archivé.');
        $this->redirect('/articles/edit?id=' . $id);
    }

    /** @param array<string,mixed> $query @param array<string,mixed> $post */
    public function destroy(array $query, array $post): void
    {
        $this->requireAuth();
        $this->verifyCsrf($post);

        $id = isset($query['id']) ? (int) $query['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo 'Bad Request';
            return;
        }

        $article = $this->articles->find($id);
        if ($article === null) {
            http_response_code(404);
            echo 'Not Found';
            return;
        }

        if (!$this->canAccessArticle($article)) {
            http_response_code(403);
            echo '403 - Forbidden';
            return;
        }

        $this->articles->delete($id);
        $this->flashStatus('Article supprimé.');
        $this->redirect('/articles');
    }

    /** @param array<string,mixed> $post @param array<string,mixed> $files */
    public function uploadTinyMceImage(array $post, array $files): void
    {
        // Clear any output buffering to prevent interfering with JSON response
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Start output buffering to catch any stray output
        ob_start();

        // Set JSON content type first to prevent any HTML error output
        header('Content-Type: application/json; charset=utf-8');

        try {
            // Verify auth - return JSON, not redirect
            if ($this->currentUser() === null) {
                http_response_code(401);
                ob_end_clean();
                echo json_encode(['error' => 'Unauthorized'], JSON_UNESCAPED_SLASHES);
                exit;
            }

            // Verify CSRF
            $token = (string) ($post['_token'] ?? '');
            if ($token === '' || !hash_equals($this->csrfToken(), $token)) {
                http_response_code(419);
                ob_end_clean();
                echo json_encode(['error' => 'CSRF token mismatch'], JSON_UNESCAPED_SLASHES);
                exit;
            }

            // Process file upload
            $file = $files['file'] ?? null;
            if (!$file || !isset($file['tmp_name'])) {
                http_response_code(400);
                ob_end_clean();
                echo json_encode(['error' => 'No file provided'], JSON_UNESCAPED_SLASHES);
                exit;
            }

            // Save the uploaded image
            $path = $this->saveUploadedImage((array) $file, 'articles/content', 5 * 1024 * 1024, ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

            if (!$path) {
                http_response_code(422);
                ob_end_clean();
                echo json_encode(['error' => 'Failed to save image. Check file type and size.'], JSON_UNESCAPED_SLASHES);
                exit;
            }

            // Success response
            http_response_code(200);
            ob_end_clean();
            echo json_encode([
                'location' => $this->url('/uploads/' . $path),
            ], JSON_UNESCAPED_SLASHES);
            exit;
        } catch (\Throwable $e) {
            // Catch any exceptions (including fatal errors converted to exceptions) and return as JSON error
            http_response_code(500);
            ob_end_clean();
            echo json_encode(['error' => 'Server error: ' . $e->getMessage()], JSON_UNESCAPED_SLASHES);
            exit;
        }
    }

    /** @param array<string,mixed> $post @return array{0: array<int,string>, 1: array<string,string>} */
    private function validate(array $post): array
    {
        $titre = trim((string) ($post['titre'] ?? ''));
        $idCat = (int) ($post['id_article_categorie'] ?? 0);
        $description = trim((string) ($post['description'] ?? ''));
        $contenu = (string) ($post['contenu'] ?? '');

        $errors = [];

        if ($titre === '') {
            $errors[] = 'Le titre est obligatoire.';
        } elseif (mb_strlen($titre) > 250) {
            $errors[] = 'Le titre ne doit pas dépasser 250 caractères.';
        }

        if ($idCat <= 0) {
            $errors[] = 'La catégorie est obligatoire.';
        }

        if (mb_strlen($description) > 1000) {
            $errors[] = 'La description ne doit pas dépasser 1000 caractères.';
        }

        $data = [
            'titre' => $titre,
            'id_article_categorie' => (string) $idCat,
            'description' => $description,
            'contenu' => $contenu,
        ];

        return [$errors, $data];
    }

    /** @param array<string,mixed> $article */
    private function canAccessArticle(array $article): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $u = $this->currentUser();
        if ($u === null) {
            return false;
        }

        return (int) ($article['id_utilisateur'] ?? 0) === (int) $u['id'];
    }

    private function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    /** @param array<string,mixed> $file */
    private function saveUploadedImage(array $file, string $subDir, int $maxBytes = 5242880, ?array $allowedMime = null): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0 || $size > $maxBytes) {
            return null;
        }

        $tmp = (string) ($file['tmp_name'] ?? '');
        if ($tmp === '' || !is_file($tmp)) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = (string) $finfo->file($tmp);

        $defaultAllowed = ['image/jpeg', 'image/png', 'image/webp'];
        $allowed = $allowedMime ?? $defaultAllowed;

        if (!in_array($mime, $allowed, true)) {
            return null;
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'bin',
        };

        $root = $this->projectRoot();
        $storageDir = $root . '/uploads/' . trim($subDir, '/');
        if (!is_dir($storageDir) && !mkdir($storageDir, 0777, true) && !is_dir($storageDir)) {
            return null;
        }

        $filename = bin2hex(random_bytes(10)) . '.' . $ext;
        $target = $storageDir . '/' . $filename;

        if (!move_uploaded_file($tmp, $target)) {
            return null;
        }

        return trim($subDir, '/') . '/' . $filename;
    }

    private function slugify(string $input): string
    {
        $s = trim($input);
        if ($s === '') {
            return '';
        }

        $s = mb_strtolower($s);
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s);
        if (is_string($converted) && $converted !== '') {
            $s = $converted;
        }

        $s = preg_replace('/[^a-z0-9]+/i', '-', $s) ?? $s;
        $s = trim($s, '-');
        $s = preg_replace('/-+/', '-', $s) ?? $s;

        return $s;
    }

    private function limit(string $s, int $max): string
    {
        if (mb_strlen($s) <= $max) {
            return $s;
        }
        return mb_substr($s, 0, $max);
    }

    private function computeMetaDescription(string $description, string $html): string
    {
        $desc = trim($description);
        if ($desc !== '') {
            return $this->limit($desc, 250);
        }

        $text = trim((string) preg_replace('/\s+/', ' ', strip_tags($html)));
        if ($text === '') {
            return '';
        }

        return $this->limit($text, 250);
    }

    private function ensureImagesHaveAlt(string $html, string $fallbackAlt): string
    {
        $fallbackAlt = trim($fallbackAlt);
        if ($fallbackAlt === '') {
            $fallbackAlt = 'Image';
        }

        $doc = new \DOMDocument();
        $previousUseInternalErrors = libxml_use_internal_errors(true);

        try {
            $wrapped = '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body>' . $html . '</body></html>';
            $doc->loadHTML($wrapped);

            $images = $doc->getElementsByTagName('img');
            foreach ($images as $img) {
                $alt = $img->getAttribute('alt');
                if (trim($alt) === '') {
                    $img->setAttribute('alt', $fallbackAlt);
                }
            }

            $body = $doc->getElementsByTagName('body')->item(0);
            if (!$body) {
                return $html;
            }

            $out = '';
            foreach ($body->childNodes as $child) {
                $out .= $doc->saveHTML($child);
            }

            return $out;
        } catch (\Throwable $e) {
            return $html;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);
        }
    }
}
