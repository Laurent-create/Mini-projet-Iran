<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        header('Location: /articles');
        exit;
    }
}
