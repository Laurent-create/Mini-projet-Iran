<?php
declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;

class Database
{
    private ?PDO $pdo = null;

    public function __construct(private readonly array $config)
    {
    }

    public function getConnection(): PDO
    {
        if ($this->pdo instanceof PDO) {
            return $this->pdo;
        }

        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $this->config['db_host'],
            $this->config['db_port'],
            $this->config['db_name']
        );

        $this->pdo = new PDO($dsn, $this->config['db_user'], $this->config['db_password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        return $this->pdo;
    }

    public function checkConnection(): array
    {
        try {
            $pdo = $this->getConnection();
            $pdo->query('SELECT 1');
            return ['ok' => true, 'message' => 'Connected to PostgreSQL'];
        } catch (PDOException $exception) {
            return ['ok' => false, 'message' => 'DB error: ' . $exception->getMessage()];
        }
    }
}
