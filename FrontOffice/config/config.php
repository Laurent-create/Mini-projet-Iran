<?php
declare(strict_types=1);

return [
    'app_name' => getenv('APP_NAME') ?: 'FrontOffice',
    'app_env' => getenv('APP_ENV') ?: 'development',
    'app_url' => rtrim((string) (getenv('APP_URL') ?: 'http://localhost:8080'), '/'),
    'db_host' => getenv('DB_HOST') ?: 'db',
    'db_port' => getenv('DB_PORT') ?: '5432',
    'db_name' => getenv('DB_NAME') ?: 'iran_info',
    'db_user' => getenv('DB_USER') ?: 'iran_user',
    'db_password' => getenv('DB_PASSWORD') ?: 'change_me_in_local_env',
];
