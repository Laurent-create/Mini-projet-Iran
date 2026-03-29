<?php
declare(strict_types=1);

namespace App\Models;

use Core\Database;
use PDO;

class ArticleModel
{
    public function __construct(private readonly array $config)
    {
    }

    public function getCategories(): array
    {
        $stmt = $this->db()->query(
            'SELECT id_article_categorie, libelle
             FROM article_categorie
             ORDER BY libelle ASC'
        );

        return $stmt->fetchAll();
    }

    public function getFeaturedArticle(): ?array
    {
        $stmt = $this->db()->prepare(
            'SELECT a.*, c.libelle AS category_label
             FROM article a
             LEFT JOIN article_categorie c ON c.id_article_categorie = a.id_article_categorie
             WHERE a.id_article_statu = 2
             ORDER BY a.date_publication DESC NULLS LAST, a.id_article DESC
             LIMIT 1'
        );
        $stmt->execute();

        $article = $stmt->fetch();
        return $article !== false ? $article : null;
    }

    public function countPublishedArticles(string $search, int $categoryId, ?int $excludeId): int
    {
        $params = [];
        $where = $this->buildWhere($search, $categoryId, $excludeId, $params);

        $stmt = $this->db()->prepare('SELECT COUNT(*) AS total FROM article a ' . $where);
        $stmt->execute($params);

        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function getPublishedArticles(
        string $search,
        int $categoryId,
        ?int $excludeId,
        int $page,
        int $perPage
    ): array {
        $params = [];
        $where = $this->buildWhere($search, $categoryId, $excludeId, $params);

        $offset = max(0, ($page - 1) * $perPage);
        $params['limit'] = $perPage;
        $params['offset'] = $offset;

        $stmt = $this->db()->prepare(
            'SELECT a.*, c.libelle AS category_label
             FROM article a
             LEFT JOIN article_categorie c ON c.id_article_categorie = a.id_article_categorie
             ' . $where . '
             ORDER BY a.date_publication DESC NULLS LAST, a.id_article DESC
             LIMIT :limit OFFSET :offset'
        );

        foreach ($params as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue(':' . $key, $value, $type);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPublishedArticleBySlug(string $slug): ?array
    {
        $stmt = $this->db()->prepare(
            'SELECT a.*, c.libelle AS category_label, u.nom AS author_name
             FROM article a
             LEFT JOIN article_categorie c ON c.id_article_categorie = a.id_article_categorie
             LEFT JOIN utilisateur u ON u.id_utilisateur = a.id_utilisateur
             WHERE a.id_article_statu = 2 AND a.slug = :slug
             LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);

        $article = $stmt->fetch();
        return $article !== false ? $article : null;
    }

    public function getActiveImagesByArticleId(int $articleId): array
    {
        $stmt = $this->db()->prepare(
            'SELECT id_article_images, url, position_, legend
             FROM article_images
             WHERE id_article = :id_article
               AND est_actif = true
             ORDER BY position_ ASC NULLS LAST, id_article_images ASC'
        );
        $stmt->execute(['id_article' => $articleId]);

        return $stmt->fetchAll();
    }

    private function buildWhere(string $search, int $categoryId, ?int $excludeId, array &$params): string
    {
        $parts = ['a.id_article_statu = 2'];

        if ($search !== '') {
            $parts[] = '(
                a.titre ILIKE :q OR
                a.contenu ILIKE :q OR
                a.meta_title ILIKE :q OR
                a.meta_description ILIKE :q
            )';
            $params['q'] = '%' . $search . '%';
        }

        if ($categoryId > 0) {
            $parts[] = 'a.id_article_categorie = :category_id';
            $params['category_id'] = $categoryId;
        }

        if ($excludeId !== null) {
            $parts[] = 'a.id_article <> :exclude_id';
            $params['exclude_id'] = $excludeId;
        }

        return 'WHERE ' . implode(' AND ', $parts);
    }

    private function db(): PDO
    {
        $database = new Database($this->config);
        return $database->getConnection();
    }
}
