<?php
/** @var string $content */
/** @var string|null $title */
/** @var string|null $baseUrl */

$baseUrl = $baseUrl ?? '';
$pageTitle = $title ?? 'BackOffice2';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= htmlspecialchars((string)($csrfToken ?? '')) ?>">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl . '/public/css/style.css') ?>">
</head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main>
    <?= $content ?>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
<script src="<?= htmlspecialchars($baseUrl . '/public/js/app.js') ?>"></script>
</body>
</html>
