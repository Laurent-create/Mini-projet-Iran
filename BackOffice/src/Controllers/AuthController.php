<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;
use PDO;

final class AuthController extends Controller
{
    private UserRepository $users;

    public function __construct(private PDO $pdo)
    {
        $this->users = new UserRepository($pdo);
    }

    public function loginForm(): void
    {
        if ($this->currentUser() !== null) {
            $this->redirect('/dashboard');
        }

        $this->renderFullPage('auth/login', [
            'title' => 'Connexion',
            'errors' => [],
            'old' => [],
        ]);
    }

    /** @param array<string,mixed> $post */
    public function login(array $post): void
    {
        $this->verifyCsrf($post);

        $email = trim((string) ($post['email'] ?? ''));
        $password = (string) ($post['password'] ?? '');

        $errors = [];
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email n'est pas valide.";
        }
        if (trim($password) === '') {
            $errors[] = 'Le mot de passe est obligatoire.';
        }

        if (!empty($errors)) {
            $this->renderFullPage('auth/login', [
                'title' => 'Connexion',
                'errors' => $errors,
                'old' => ['email' => $email],
            ]);
            return;
        }

        $user = $this->users->findForAuthByEmail($email);
        if ($user === null || !hash_equals((string) $user['pass'], (string) $password)) {
            $this->renderFullPage('auth/login', [
                'title' => 'Connexion',
                'errors' => ['Identifiants incorrects.'],
                'old' => ['email' => $email],
            ]);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'email' => (string) $user['email'],
            'type' => (int) $user['type'],
        ];

        $this->redirect('/dashboard');
    }

    public function registerForm(): void
    {
        if ($this->currentUser() !== null) {
            $this->redirect('/dashboard');
        }

        $this->renderFullPage('auth/register', [
            'title' => 'Inscription',
            'errors' => [],
            'old' => [],
        ]);
    }

    /** @param array<string,mixed> $post */
    public function register(array $post): void
    {
        $this->verifyCsrf($post);

        $nom = trim((string) ($post['nom'] ?? ''));
        $email = trim((string) ($post['email'] ?? ''));
        $password = (string) ($post['password'] ?? '');
        $passwordConfirmation = (string) ($post['password_confirmation'] ?? '');

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
        } elseif ($this->users->emailExists($email)) {
            $errors[] = "Cet email est déjà utilisé.";
        }

        if (trim($password) === '') {
            $errors[] = 'Le mot de passe est obligatoire.';
        } elseif (mb_strlen($password) > 50) {
            $errors[] = 'Le mot de passe ne doit pas dépasser 50 caractères.';
        } elseif ($password !== $passwordConfirmation) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }

        if (!empty($errors)) {
            $this->renderFullPage('auth/register', [
                'title' => 'Inscription',
                'errors' => $errors,
                'old' => ['nom' => $nom, 'email' => $email],
            ]);
            return;
        }

        // Par défaut: Rédacteur (id=2 dans le schéma/seed)
        $this->users->create($nom, $email, 2, $password);
        $this->redirect('/login');
    }

    /** @param array<string,mixed> $post */
    public function logout(array $post): void
    {
        $this->verifyCsrf($post);
        unset($_SESSION['user']);
        session_regenerate_id(true);
        $this->redirect('/login');
    }
}
