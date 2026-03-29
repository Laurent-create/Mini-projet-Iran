<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        $baseUrl = $data['baseUrl'] ?? $this->baseUrl();
        $currentUser = $data['currentUser'] ?? $this->currentUser();
        $csrfToken = $data['csrfToken'] ?? $this->csrfToken();

        $viewFile = __DIR__ . '/../../views/' . ltrim($view, '/') . '.php';
        if (!is_file($viewFile)) {
            throw new \RuntimeException('View not found: ' . $view);
        }

        $data = array_merge(
            [
                'baseUrl' => $baseUrl,
                'currentUser' => $currentUser,
                'csrfToken' => $csrfToken,
            ],
            $data
        );

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = __DIR__ . '/../../views/layouts/main.php';
        require $layoutFile;
    }

    /** @return array{id:int,email:string,type:int}|null */
    protected function currentUser(): ?array
    {
        $u = $_SESSION['user'] ?? null;
        if (!is_array($u)) {
            return null;
        }

        $id = (int) ($u['id'] ?? 0);
        $email = (string) ($u['email'] ?? '');
        $type = (int) ($u['type'] ?? 0);

        if ($id <= 0 || $email === '' || $type <= 0) {
            return null;
        }

        return ['id' => $id, 'email' => $email, 'type' => $type];
    }

    protected function isAdmin(): bool
    {
        $u = $this->currentUser();
        return $u !== null && (int) $u['type'] === 1;
    }

    protected function requireAuth(): void
    {
        if ($this->currentUser() === null) {
            $this->redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireAuth();
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo '403 - Forbidden';
            exit;
        }
    }

    protected function csrfToken(): string
    {
        if (!isset($_SESSION['_csrf']) || !is_string($_SESSION['_csrf']) || $_SESSION['_csrf'] === '') {
            $_SESSION['_csrf'] = bin2hex(random_bytes(16));
        }
        return (string) $_SESSION['_csrf'];
    }

    protected function verifyCsrf(array $post): void
    {
        $token = (string) ($post['_token'] ?? '');
        if ($token === '' || !hash_equals($this->csrfToken(), $token)) {
            http_response_code(419);
            echo '419 - CSRF token mismatch';
            exit;
        }
    }

    protected function baseUrl(): string
    {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
        $base = rtrim(dirname($scriptName), '/');
        return $base === '/' ? '' : $base;
    }

    protected function url(string $path): string
    {
        return $this->baseUrl() . $path;
    }

    protected function asset(string $path): string
    {
        return $this->baseUrl() . '/' . ltrim($path, '/');
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . $this->url($path));
        exit;
    }

    protected function flashStatus(string $message): void
    {
        $_SESSION['status'] = $message;
    }
}
