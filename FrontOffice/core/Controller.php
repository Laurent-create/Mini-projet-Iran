<?php
declare(strict_types=1);

namespace Core;

abstract class Controller
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    protected function view(string $view, array $data = []): void
    {
        $viewFile = dirname(__DIR__) . '/app/Views/' . $view . '.php';

        if (!is_file($viewFile)) {
            http_response_code(500);
            echo 'View not found';
            return;
        }

        extract($data);
        require $viewFile;
    }
}
