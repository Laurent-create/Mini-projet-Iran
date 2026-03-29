<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\HealthModel;
use Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $healthModel = new HealthModel($this->config);
        $dbStatus = $healthModel->databaseStatus();

        $this->view('home', [
            'title' => 'FrontOffice Home',
            'hello' => 'Bonjour depuis le FrontOffice',
            'dbStatus' => $dbStatus,
            'config' => $this->config,
        ]);
    }
}
