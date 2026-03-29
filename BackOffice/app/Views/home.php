<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; background: #f4f6f8; }
        .card { background: #fff; border: 1px solid #d8dee4; border-radius: 8px; padding: 1.5rem; max-width: 720px; }
        .ok { color: #0a7a2d; font-weight: bold; }
        .ko { color: #b00020; font-weight: bold; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <div class="card">
        <h1><?= htmlspecialchars($hello) ?></h1>
        <p class="muted">Application: <?= htmlspecialchars($config['app_name']) ?> (<?= htmlspecialchars($config['app_env']) ?>)</p>
        <p>
            Database status:
            <span class="<?= $dbStatus['ok'] ? 'ok' : 'ko' ?>"><?= htmlspecialchars($dbStatus['message']) ?></span>
        </p>
        <p class="muted">Try route: /home/index</p>
    </div>
</body>
</html>
