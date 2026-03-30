<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;
use PDO;

final class UserController extends Controller
{
    private UserRepository $users;

    public function __construct(private PDO $pdo)
    {
        $this->users = new UserRepository($pdo);
    }

    public function index(): void
    {
        $this->requireAdmin();

        $filters = [
            'q' => trim((string) ($_GET['q'] ?? '')),
            'type' => isset($_GET['type']) ? (int) $_GET['type'] : 0,
        ];

        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

        $result = $this->users->paginate($filters, $page, 10);

        $this->render('users/index', [
            'title' => 'Utilisateurs',
            'baseUrl' => $this->baseUrl(),
            'users' => $result['items'],
            'pagination' => $result,
            'filters' => $filters,
            'types' => $this->users->types(),
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $this->render('users/create', [
            'title' => 'Nouvel utilisateur',
            'baseUrl' => $this->baseUrl(),
            'types' => $this->users->types(),
            'errors' => [],
            'old' => [],
        ]);
    }

    /** @param array<string, mixed> $post */
    public function store(array $post): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($post);

        [$errors, $data] = $this->validate($post, true);

        if (!empty($errors)) {
            $this->render('users/create', [
                'title' => 'Nouvel utilisateur',
                'baseUrl' => $this->baseUrl(),
                'types' => $this->users->types(),
                'errors' => $errors,
                'old' => $data,
            ]);
            return;
        }

        $id = $this->users->create($data['nom'], $data['email'], (int) $data['id_type_utilisateur'], $data['mot_de_passe'] ?? null);
        $this->redirect('/users/show?id=' . $id);
    }

    /** @param array<string, mixed> $query */
    public function edit(array $query): void
    {
        $this->requireAdmin();

        $id = isset($query['id']) ? (int) $query['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo 'Bad Request';
            return;
        }

        $user = $this->users->find($id);
        if ($user === null) {
            http_response_code(404);
            echo 'Not Found';
            return;
        }

        $this->render('users/edit', [
            'title' => 'Éditer utilisateur',
            'baseUrl' => $this->baseUrl(),
            'types' => $this->users->types(),
            'errors' => [],
            'old' => [
                'nom' => $user->nom,
                'email' => $user->email,
                'id_type_utilisateur' => (string) $user->id_type_utilisateur,
            ],
            'userId' => $user->id_utilisateur,
        ]);
    }

    /** @param array<string, mixed> $query @param array<string, mixed> $post */
    public function update(array $query, array $post): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($post);

        $id = isset($query['id']) ? (int) $query['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo 'Bad Request';
            return;
        }

        $existing = $this->users->find($id);
        if ($existing === null) {
            http_response_code(404);
            echo 'Not Found';
            return;
        }

        [$errors, $data] = $this->validate($post, false);

        if (!empty($errors)) {
            $this->render('users/edit', [
                'title' => 'Éditer utilisateur',
                'baseUrl' => $this->baseUrl(),
                'types' => $this->users->types(),
                'errors' => $errors,
                'old' => $data,
                'userId' => $id,
            ]);
            return;
        }

        $motDePasse = $data['mot_de_passe'] ?? null;
        if ($motDePasse === '') {
            $motDePasse = null;
        }

        $this->users->update($id, $data['nom'], $data['email'], (int) $data['id_type_utilisateur'], $motDePasse);
        $this->redirect('/users/show?id=' . $id);
    }

    /** @param array<string, mixed> $query */
    public function show(array $query): void
    {
        $this->requireAdmin();

        $id = isset($query['id']) ? (int) $query['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo 'Bad Request';
            return;
        }

        $user = $this->users->find($id);
        if ($user === null) {
            http_response_code(404);
            echo 'Not Found';
            return;
        }

        $types = $this->users->types();
        $typeLabel = null;
        foreach ($types as $t) {
            if ((int) $t['id_type_utilisateur'] === (int) $user->id_type_utilisateur) {
                $typeLabel = (string) $t['libelle'];
                break;
            }
        }

        $this->render('users/show', [
            'title' => 'Détail utilisateur',
            'baseUrl' => $this->baseUrl(),
            'user' => $user,
            'typeLabel' => $typeLabel,
        ]);
    }

    /**
     * @param array<string, mixed> $post
     * @return array{0: array<int, string>, 1: array<string, string>}
     */
    private function validate(array $post, bool $isCreate): array
    {
        $nom = trim((string) ($post['nom'] ?? ''));
        $email = trim((string) ($post['email'] ?? ''));
        $type = trim((string) ($post['id_type_utilisateur'] ?? ''));
        $motDePasse = (string) ($post['mot_de_passe'] ?? '');

        $errors = [];

        if ($nom === '') {
            $errors[] = 'Le nom est obligatoire.';
        } elseif (mb_strlen($nom) > 50) {
            $errors[] = 'Le nom ne doit pas dépasser 50 caractères.';
        }

        if ($email === '') {
            $errors[] = "L'email est obligatoire.";
        } elseif (mb_strlen($email) > 100) {
            $errors[] = "L'email ne doit pas dépasser 100 caractères.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email n'est pas valide.";
        }

        $idType = (int) $type;
        if ($idType <= 0) {
            $errors[] = 'Le type utilisateur est obligatoire.';
        }

        if ($isCreate) {
            if (trim($motDePasse) === '') {
                $errors[] = 'Le mot de passe est obligatoire.';
            }
        }

        if (mb_strlen($motDePasse) > 50) {
            $errors[] = 'Le mot de passe ne doit pas dépasser 50 caractères.';
        }

        $data = [
            'nom' => $nom,
            'email' => $email,
            'id_type_utilisateur' => (string) $idType,
            'mot_de_passe' => $motDePasse,
        ];

        return [$errors, $data];
    }
}
