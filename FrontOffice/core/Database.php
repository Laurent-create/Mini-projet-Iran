<?php
declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;

class Database
{
    public function __construct(private readonly array $config)
    {
    }

    public function checkConnection(): array
    {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $this->config['db_host'],
            $this->config['db_port'],
            $this->config['db_name']
        );

        try {
            $pdo = new PDO($dsn, $this->config['db_user'], $this->config['db_password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $pdo->query('SELECT 1');
            return ['ok' => true, 'message' => 'Connected to PostgreSQL'];
        } catch (PDOException $exception) {
            return ['ok' => false, 'message' => 'DB error: ' . $exception->getMessage()];
        }
    }
}
