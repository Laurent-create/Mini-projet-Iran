<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\UserModel;
use PDO;

final class UserRepository extends Repository
{
    /** @return array<int, UserModel> */
    public function all(int $limit = 200): array
    {
        $stmt = $this->pdo->prepare('SELECT id_utilisateur, nom, email, id_type_utilisateur FROM utilisateur ORDER BY id_utilisateur DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();
        $users = [];

        foreach ($rows as $row) {
            $user = new UserModel();
            $user->id_utilisateur = (int) $row['id_utilisateur'];
            $user->nom = (string) $row['nom'];
            $user->email = (string) $row['email'];
            $user->id_type_utilisateur = (int) $row['id_type_utilisateur'];
            $users[] = $user;
        }

        return $users;
    }

    /** @param array<string, mixed> $filters */
    public function paginate(array $filters, int $page, int $perPage = 10): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $where = [];
        $params = [];

        $q = trim((string) ($filters['q'] ?? ''));
        if ($q !== '') {
            $where[] = '(u.nom ILIKE :q OR u.email ILIKE :q)';
            $params[':q'] = '%' . $q . '%';
        }

        $type = (int) ($filters['type'] ?? 0);
        if ($type > 0) {
            $where[] = 'u.id_type_utilisateur = :type';
            $params[':type'] = $type;
        }

        $whereSql = empty($where) ? '' : ('WHERE ' . implode(' AND ', $where));

        $countStmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM utilisateur u ' . $whereSql
        );
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $sql =
            'SELECT u.id_utilisateur, u.nom, u.email, u.id_type_utilisateur, u.date_creation ' .
            'FROM utilisateur u ' .
            $whereSql . ' ' .
            'ORDER BY u.id_utilisateur DESC LIMIT :limit OFFSET :offset';

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();
        $users = [];

        foreach ($rows as $row) {
            $user = new UserModel();
            $user->id_utilisateur = (int) $row['id_utilisateur'];
            $user->nom = (string) $row['nom'];
            $user->email = (string) $row['email'];
            $user->id_type_utilisateur = (int) $row['id_type_utilisateur'];
            $users[] = $user;
        }

        return [
            'items' => $users,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'pages' => (int) ceil($total / $perPage),
        ];
    }

    public function find(int $id): ?UserModel
    {
        $stmt = $this->pdo->prepare('SELECT id_utilisateur, nom, email, id_type_utilisateur FROM utilisateur WHERE id_utilisateur = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $user = new UserModel();
        $user->id_utilisateur = (int) $row['id_utilisateur'];
        $user->nom = (string) $row['nom'];
        $user->email = (string) $row['email'];
        $user->id_type_utilisateur = (int) $row['id_type_utilisateur'];
        return $user;
    }

    /** @return array<int, array{id_type_utilisateur:int, libelle:string}> */
    public function types(): array
    {
        $stmt = $this->pdo->query('SELECT id_type_utilisateur, libelle FROM type_utilisateur ORDER BY id_type_utilisateur ASC');
        $rows = $stmt->fetchAll();

        $types = [];
        foreach ($rows as $row) {
            $types[] = [
                'id_type_utilisateur' => (int) $row['id_type_utilisateur'],
                'libelle' => (string) $row['libelle'],
            ];
        }

        return $types;
    }

    public function create(string $nom, string $email, int $idTypeUtilisateur, ?string $motDePasse): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO utilisateur (nom, email, id_type_utilisateur, mot_de_passe, date_creation) VALUES (:nom, :email, :type, :pass, CURRENT_DATE) RETURNING id_utilisateur');
        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':type' => $idTypeUtilisateur,
            ':pass' => $motDePasse ?? '',
        ]);

        $id = $stmt->fetchColumn();
        return (int) $id;
    }

    public function update(int $id, string $nom, string $email, int $idTypeUtilisateur, ?string $motDePasse): void
    {
        if ($motDePasse !== null && $motDePasse !== '') {
            $stmt = $this->pdo->prepare('UPDATE utilisateur SET nom = :nom, email = :email, id_type_utilisateur = :type, mot_de_passe = :pass WHERE id_utilisateur = :id');
            $stmt->execute([
                ':id' => $id,
                ':nom' => $nom,
                ':email' => $email,
                ':type' => $idTypeUtilisateur,
                ':pass' => $motDePasse,
            ]);
            return;
        }

        $stmt = $this->pdo->prepare('UPDATE utilisateur SET nom = :nom, email = :email, id_type_utilisateur = :type WHERE id_utilisateur = :id');
        $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':email' => $email,
            ':type' => $idTypeUtilisateur,
        ]);
    }

    /** @return array{id:int,nom:string,email:string,type:int,pass:string}|null */
    public function findForAuthByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id_utilisateur, nom, email, id_type_utilisateur, mot_de_passe FROM utilisateur WHERE LOWER(email) = LOWER(:email) LIMIT 1');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return [
            'id' => (int) $row['id_utilisateur'],
            'nom' => (string) $row['nom'],
            'email' => (string) $row['email'],
            'type' => (int) $row['id_type_utilisateur'],
            'pass' => (string) ($row['mot_de_passe'] ?? ''),
        ];
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT 1 FROM utilisateur WHERE LOWER(email) = LOWER(:email) LIMIT 1');
        $stmt->execute([':email' => $email]);
        return (bool) $stmt->fetchColumn();
    }
}
