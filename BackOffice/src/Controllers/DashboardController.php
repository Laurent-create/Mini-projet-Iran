<?php

declare(strict_types=1);

namespace App\Controllers;

use PDO;

final class DashboardController extends Controller
{
    public function __construct(private PDO $pdo)
    {
    }

    public function index(): void
    {
        $this->requireAuth();

        $this->render('dashboard/index', [
            'title' => 'Dashboard',
        ]);
    }
}
