<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

abstract class Repository
{
    public function __construct(protected PDO $pdo)
    {
    }
}
