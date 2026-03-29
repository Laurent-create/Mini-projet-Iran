<?php

declare(strict_types=1);

namespace App\Repositories;

final class ArticleStatusRepository extends Repository
{
    /** @return array<int, array{id:int, libelle:string}> */
    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id_article_statu, libelle FROM article_statu ORDER BY id_article_statu ASC');
        $rows = $stmt->fetchAll();

        $out = [];
        foreach ($rows as $row) {
            $out[] = ['id' => (int) $row['id_article_statu'], 'libelle' => (string) $row['libelle']];
        }

        return $out;
    }
}
