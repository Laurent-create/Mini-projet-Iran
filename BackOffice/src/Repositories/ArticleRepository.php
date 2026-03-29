<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ArticleRepository extends Repository
{
    /** @param array<string,mixed> $filters */
    public function paginate(array $filters, ?int $forceAuthorId, int $page, int $perPage): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $where = [];
        $params = [];

        $q = trim((string) ($filters['q'] ?? ''));
        if ($q !== '') {
            $where[] = '(a.titre ILIKE :q OR a.slug ILIKE :q)';
            $params[':q'] = '%' . $q . '%';
        }

        $category = (int) ($filters['category'] ?? 0);
        if ($category > 0) {
            $where[] = 'a.id_article_categorie = :cat';
            $params[':cat'] = $category;
        }

        $status = (int) ($filters['status'] ?? 0);
        if ($status > 0) {
            $where[] = 'a.id_article_statu = :st';
            $params[':st'] = $status;
        }

        $author = (int) ($filters['author'] ?? 0);
        if ($forceAuthorId !== null && $forceAuthorId > 0) {
            $where[] = 'a.id_utilisateur = :forceAuthor';
            $params[':forceAuthor'] = $forceAuthorId;
        } elseif ($author > 0) {
            $where[] = 'a.id_utilisateur = :author';
            $params[':author'] = $author;
        }

        $whereSql = empty($where) ? '' : ('WHERE ' . implode(' AND ', $where));

        $countStmt = $this->pdo->prepare(
            'SELECT COUNT(*) ' .
            'FROM article a ' .
            'JOIN article_categorie c ON c.id_article_categorie = a.id_article_categorie ' .
            'JOIN article_statu s ON s.id_article_statu = a.id_article_statu ' .
            'JOIN utilisateur u ON u.id_utilisateur = a.id_utilisateur ' .
            $whereSql
        );
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $sql =
            'SELECT ' .
            ' a.id_article, a.titre, a.slug, a.image_principale, a.date_creation, a.date_publication, a.meta_description, ' .
            ' a.id_article_categorie, c.libelle AS categorie_libelle, ' .
            ' a.id_article_statu, s.libelle AS statu_libelle, ' .
            ' a.id_utilisateur, u.nom AS auteur_nom, u.email AS auteur_email ' .
            'FROM article a ' .
            'JOIN article_categorie c ON c.id_article_categorie = a.id_article_categorie ' .
            'JOIN article_statu s ON s.id_article_statu = a.id_article_statu ' .
            'JOIN utilisateur u ON u.id_utilisateur = a.id_utilisateur ' .
            $whereSql . ' ' .
            'ORDER BY a.id_article DESC LIMIT :limit OFFSET :offset';

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $items = $stmt->fetchAll();
        $pages = (int) max(1, (int) ceil($total / $perPage));

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'pages' => $pages,
        ];
    }

    /** @return array<string,mixed>|null */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*,
                    c.libelle AS categorie_libelle,
                    s.libelle AS statu_libelle,
                    u.nom AS auteur_nom,
                    u.email AS auteur_email
             FROM article a
             JOIN article_categorie c ON c.id_article_categorie = a.id_article_categorie
             JOIN article_statu s ON s.id_article_statu = a.id_article_statu
             JOIN utilisateur u ON u.id_utilisateur = a.id_utilisateur
             WHERE a.id_article = :id'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /** @param array<string,mixed> $data */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO article (titre, contenu, slug, image_principale, date_creation, date_publication, meta_title, meta_description, id_article_categorie, id_article_statu, id_utilisateur)
             VALUES (:titre, :contenu, :slug, :image, CURRENT_DATE, NULL, :meta_title, :meta_description, :cat, :statu, :author)
             RETURNING id_article'
        );

        $stmt->execute([
            ':titre' => (string) $data['titre'],
            ':contenu' => (string) ($data['contenu'] ?? ''),
            ':slug' => (string) $data['slug'],
            ':image' => (string) ($data['image_principale'] ?? ''),
            ':meta_title' => (string) ($data['meta_title'] ?? ''),
            ':meta_description' => (string) ($data['meta_description'] ?? ''),
            ':cat' => (int) $data['id_article_categorie'],
            ':statu' => (int) ($data['id_article_statu'] ?? 1),
            ':author' => (int) $data['id_utilisateur'],
        ]);

        return (int) $stmt->fetchColumn();
    }

    /** @param array<string,mixed> $data */
    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE article
             SET titre = :titre,
                 contenu = :contenu,
                 slug = :slug,
                 image_principale = :image,
                 meta_title = :meta_title,
                 meta_description = :meta_description,
                 id_article_categorie = :cat
             WHERE id_article = :id'
        );

        $stmt->execute([
            ':id' => $id,
            ':titre' => (string) $data['titre'],
            ':contenu' => (string) ($data['contenu'] ?? ''),
            ':slug' => (string) $data['slug'],
            ':image' => (string) ($data['image_principale'] ?? ''),
            ':meta_title' => (string) ($data['meta_title'] ?? ''),
            ':meta_description' => (string) ($data['meta_description'] ?? ''),
            ':cat' => (int) $data['id_article_categorie'],
        ]);
    }

    public function publish(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE article SET id_article_statu = 2, date_publication = NOW() WHERE id_article = :id');
        $stmt->execute([':id' => $id]);
    }

    public function archive(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE article SET id_article_statu = 3 WHERE id_article = :id');
        $stmt->execute([':id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmtImgs = $this->pdo->prepare('DELETE FROM article_images WHERE id_article = :id');
        $stmtImgs->execute([':id' => $id]);

        $stmt = $this->pdo->prepare('DELETE FROM article WHERE id_article = :id');
        $stmt->execute([':id' => $id]);
    }

    /** @return array<int, array{id:int, nom:string, email:string}> */
    public function authors(): array
    {
        $stmt = $this->pdo->query('SELECT id_utilisateur, nom, email FROM utilisateur ORDER BY nom ASC');
        $rows = $stmt->fetchAll();
        $out = [];
        foreach ($rows as $row) {
            $out[] = ['id' => (int) $row['id_utilisateur'], 'nom' => (string) $row['nom'], 'email' => (string) $row['email']];
        }
        return $out;
    }
}
