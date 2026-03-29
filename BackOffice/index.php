<?php
declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'App\\' => __DIR__ . '/app/',
        'Core\\' => __DIR__ . '/core/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $length = strlen($prefix);
        if (strncmp($prefix, $class, $length) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $length);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (is_file($file)) {
            require_once $file;
        }
    }
});

$config = require __DIR__ . '/config/config.php';
$route = $_GET['url'] ?? 'home/index';
$segments = explode('/', trim($route, '/'));
$controllerName = ucfirst($segments[0] ?: 'home') . 'Controller';
$method = $segments[1] ?? 'index';

$controllerClass = 'App\\Controllers\\' . $controllerName;
if (!class_exists($controllerClass)) {
    http_response_code(404);
    echo 'Controller not found';
    exit;
}

$controller = new $controllerClass($config);
if (!method_exists($controller, $method)) {
    http_response_code(404);
    echo 'Action not found';
    exit;
}

$controller->{$method}();
