<?php
declare(strict_types=1);

namespace App\Models;

use Core\Database;

class HealthModel
{
    public function __construct(private readonly array $config)
    {
    }

    public function databaseStatus(): array
    {
        $database = new Database($this->config);
        return $database->checkConnection();
    }
}
