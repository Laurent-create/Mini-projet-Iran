<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\UserController;
use App\Controllers\ArticleController;
use App\Controllers\CategoryController;



// ---- Bootstrap ----

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

/** Load simple KEY=VALUE lines from .env into the process environment. */
$loadEnv = static function (string $filePath): void {
	if (!is_file($filePath)) {
		return;
	}

	$lines = file($filePath, FILE_IGNORE_NEW_LINES);
	if (!is_array($lines)) {
		return;
	}

	foreach ($lines as $line) {
		$line = trim((string) $line);
		if ($line === '' || str_starts_with($line, '#')) {
			continue;
		}
		if (!str_contains($line, '=')) {
			continue;
		}

		[$name, $value] = explode('=', $line, 2);
		$name = trim($name);
		$value = trim($value);

		if ($name === '') {
			continue;
		}

		putenv($name . '=' . $value);
		$_ENV[$name] = $value;
	}
};

$loadEnv(__DIR__ . '/.env');

spl_autoload_register(static function (string $class): void {
	$prefix = 'App\\';
	if (!str_starts_with($class, $prefix)) {
		return;
	}

	$relative = substr($class, strlen($prefix));
	$file = __DIR__ . '/src/' . str_replace('\\', '/', $relative) . '.php';
	if (is_file($file)) {
		require $file;
	}
});

try {
	$pdo = require __DIR__ . '/config/database.php';
} catch (Throwable $e) {
	http_response_code(500);
	header('Content-Type: text/html; charset=utf-8');
	echo '<h1>500 - Erreur serveur</h1>';
	echo '<pre style="white-space:pre-wrap;">' . htmlspecialchars($e->getMessage()) . '</pre>';
	exit;
}

// ---- Routing ----

$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
$uriPath = (string) (parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?? '/');

$scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
$baseDir = rtrim(dirname($scriptName), '/');

$path = $uriPath;
if ($baseDir !== '' && $baseDir !== '/' && str_starts_with($path, $baseDir)) {
	$path = substr($path, strlen($baseDir)) ?: '/';
}
if ($path === '') {
	$path = '/';
}

$query = $_GET;
$post = $_POST;
$files = $_FILES;

$isLoggedIn = isset($_SESSION['user']) && is_array($_SESSION['user']);

if ($path === '/') {
	header('Location: ' . ($baseDir === '/' ? '' : $baseDir) . ($isLoggedIn ? '/accueil' : '/login'));
	exit;
}

// Auth
if ($path === '/login' && $method === 'GET') {
	(new AuthController($pdo))->loginForm();
	exit;
}
if ($path === '/login' && $method === 'POST') {
	(new AuthController($pdo))->login($post);
	exit;
}

if ($path === '/register' && $method === 'GET') {
	(new AuthController($pdo))->registerForm();
	exit;
}
if ($path === '/register' && $method === 'POST') {
	(new AuthController($pdo))->register($post);
	exit;
}

if ($path === '/logout' && $method === 'POST') {
	(new AuthController($pdo))->logout($post);
	exit;
}

// Accueil
if ($path === '/accueil' && $method === 'GET') {
	(new DashboardController($pdo))->index();
	exit;
}

// Users (admin only enforced inside controller)
if ($path === '/users' && $method === 'GET') {
	(new UserController($pdo))->index();
	exit;
}
if ($path === '/users/create' && $method === 'GET') {
	(new UserController($pdo))->create();
	exit;
}
if ($path === '/users/create' && $method === 'POST') {
	(new UserController($pdo))->store($post);
	exit;
}
if ($path === '/users/edit' && $method === 'GET') {
	(new UserController($pdo))->edit($query);
	exit;
}
if ($path === '/users/edit' && $method === 'POST') {
	(new UserController($pdo))->update($query, $post);
	exit;
}
if ($path === '/users/show' && $method === 'GET') {
	(new UserController($pdo))->show($query);
	exit;
}

// Articles
if ($path === '/articles' && $method === 'GET') {
    (new ArticleController($pdo))->index($query);
    exit;
}
if ($path === '/articles/create' && $method === 'GET') {
    (new ArticleController($pdo))->create();
    exit;
}
if ($path === '/articles/create' && $method === 'POST') {
    (new ArticleController($pdo))->store($post, $files);
    exit;
}
if ($path === '/articles/edit' && $method === 'GET') {
    (new ArticleController($pdo))->edit($query);
    exit;
}
if ($path === '/articles/edit' && $method === 'POST') {
    (new ArticleController($pdo))->update($query, $post, $files);
    exit;
}
if ($path === '/articles/publish' && $method === 'POST') {
    (new ArticleController($pdo))->publish($query, $post);
    exit;
}
if ($path === '/articles/archive' && $method === 'POST') {
    (new ArticleController($pdo))->archive($query, $post);
    exit;
}
if ($path === '/articles/destroy' && $method === 'POST') {
    (new ArticleController($pdo))->destroy($query, $post);
    exit;
}
if ($path === '/articles/upload-tinymce' && $method === 'POST') {
    (new ArticleController($pdo))->uploadTinyMceImage($post, $files);
    exit;
}

// Categories
if ($path === '/categories' && $method === 'GET') {
    (new CategoryController($pdo))->index($query);
    exit;
}
if ($path === '/categories/create' && $method === 'GET') {
    (new CategoryController($pdo))->create();
    exit;
}
if ($path === '/categories/create' && $method === 'POST') {
    (new CategoryController($pdo))->store($post);
    exit;
}
if ($path === '/categories/edit' && $method === 'GET') {
    (new CategoryController($pdo))->edit($query);
    exit;
}
if ($path === '/categories/edit' && $method === 'POST') {
    (new CategoryController($pdo))->update($query, $post);
    exit;
}
if ($path === '/categories/destroy' && $method === 'POST') {
    (new CategoryController($pdo))->destroy($query, $post);
    exit;
}

