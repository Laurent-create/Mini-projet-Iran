<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use PDO;

final class CategoryController extends Controller
{
    private CategoryRepository $categories;

    public function __construct(private PDO $pdo)
    {
        $this->categories = new CategoryRepository($pdo);
    }

    /** @param array<string,mixed> $query */
    public function index(array $query): void
    {
        $this->requireAdmin();

        $filters = [
            'id' => isset($query['id']) ? (int) $query['id'] : 0,
            'q' => trim((string) ($query['q'] ?? '')),
        ];

        $page = isset($query['page']) ? max(1, (int) $query['page']) : 1;

        $result = $this->categories->paginate($filters, $page, 10);

        $this->render('categories/index', [
            'title' => 'Catégories',
            'categories' => $result['items'],
            'pagination' => $result,
            'filters' => $filters,
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $this->render('categories/create', [
            'title' => 'Nouvelle catégorie',
            'errors' => [],
            'old' => [],
        ]);
    }

    /** @param array<string,mixed> $post */
    public function store(array $post): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($post);

        [$errors, $data] = $this->validate($post);

        if (!empty($errors)) {
            $this->render('categories/create', [
                'title' => 'Nouvelle catégorie',
                'errors' => $errors,
                'old' => $data,
            ]);
            return;
        }

        $this->categories->create($data['libelle']);
        $this->flashStatus('Catégorie créée.');
        $this->redirect('/categories');
    }

    /** @param array<string,mixed> $query */
    public function edit(array $query): void
    {
        $this->requireAdmin();

        $id = isset($query['id']) ? (int) $query['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo 'Bad Request';
            return;
        }

        $cat = $this->categories->find($id);
        if ($cat === null) {
            http_response_code(404);
            echo 'Not Found';
            return;
        }

        $this->render('categories/edit', [
            'title' => 'Éditer catégorie',
            'categoryId' => $cat['id'],
            'errors' => [],
            'old' => ['libelle' => (string) $cat['libelle']],
        ]);
    }

    /** @param array<string,mixed> $query @param array<string,mixed> $post */
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

        if ($this->categories->find($id) === null) {
            http_response_code(404);
            echo 'Not Found';
            return;
        }

        [$errors, $data] = $this->validate($post);

        if (!empty($errors)) {
            $this->render('categories/edit', [
                'title' => 'Éditer catégorie',
                'categoryId' => $id,
                'errors' => $errors,
                'old' => $data,
            ]);
            return;
        }

        $this->categories->update($id, $data['libelle']);
        $this->flashStatus('Catégorie mise à jour.');
        $this->redirect('/categories');
    }

    /** @param array<string,mixed> $query @param array<string,mixed> $post */
    public function destroy(array $query, array $post): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($post);

        $id = isset($query['id']) ? (int) $query['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo 'Bad Request';
            return;
        }

        $this->categories->delete($id);
        $this->flashStatus('Catégorie supprimée.');
        $this->redirect('/categories');
    }

    /** @param array<string,mixed> $post @return array{0: array<int,string>, 1: array{libelle:string}} */
    private function validate(array $post): array
    {
        $libelle = trim((string) ($post['libelle'] ?? ''));
        $errors = [];

        if ($libelle === '') {
            $errors[] = 'Le libellé est obligatoire.';
        } elseif (mb_strlen($libelle) > 50) {
            $errors[] = 'Le libellé ne doit pas dépasser 50 caractères.';
        }

        return [$errors, ['libelle' => $libelle]];
    }
}
