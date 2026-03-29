<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class CategoryRepository extends Repository
{
    /** @return array<int, array{id:int, libelle:string}> */
    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id_article_categorie, libelle FROM article_categorie ORDER BY id_article_categorie ASC');
        $rows = $stmt->fetchAll();

        $out = [];
        foreach ($rows as $row) {
            $out[] = ['id' => (int) $row['id_article_categorie'], 'libelle' => (string) $row['libelle']];
        }
        return $out;
    }

    /** @param array<string,mixed> $filters */
    public function paginate(array $filters, int $page, int $perPage): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(200, $perPage));
        $offset = ($page - 1) * $perPage;

        $where = [];
        $params = [];

        $id = isset($filters['id']) ? (int) $filters['id'] : 0;
        if ($id > 0) {
            $where[] = 'id_article_categorie = :id';
            $params[':id'] = $id;
        }

        $q = trim((string) ($filters['q'] ?? ''));
        if ($q !== '') {
            $where[] = 'libelle ILIKE :q';
            $params[':q'] = '%' . $q . '%';
        }

        $whereSql = empty($where) ? '' : ('WHERE ' . implode(' AND ', $where));

        $countStmt = $this->pdo->prepare('SELECT COUNT(*) FROM article_categorie ' . $whereSql);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $sql = 'SELECT id_article_categorie, libelle FROM article_categorie ' . $whereSql . ' ORDER BY id_article_categorie DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();
        $items = [];
        foreach ($rows as $row) {
            $items[] = ['id' => (int) $row['id_article_categorie'], 'libelle' => (string) $row['libelle']];
        }

        $pages = (int) max(1, (int) ceil($total / $perPage));

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'pages' => $pages,
        ];
    }

    /** @return array{id:int, libelle:string}|null */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id_article_categorie, libelle FROM article_categorie WHERE id_article_categorie = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return ['id' => (int) $row['id_article_categorie'], 'libelle' => (string) $row['libelle']];
    }

    public function create(string $libelle): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO article_categorie (libelle) VALUES (:libelle) RETURNING id_article_categorie');
        $stmt->execute([':libelle' => $libelle]);
        return (int) $stmt->fetchColumn();
    }

    public function update(int $id, string $libelle): void
    {
        $stmt = $this->pdo->prepare('UPDATE article_categorie SET libelle = :libelle WHERE id_article_categorie = :id');
        $stmt->execute([':id' => $id, ':libelle' => $libelle]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM article_categorie WHERE id_article_categorie = :id');
        $stmt->execute([':id' => $id]);
    }
}
