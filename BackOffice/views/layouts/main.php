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
    <link rel="icon" type="image/png" sizes="64x64" href="/uploads/iran_focus_logo_64.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/uploads/iran_focus_logo_180.png">
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl . '/public/css/style.css') ?>">
</head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main>
    <?php if (!empty($_SESSION['status'])): ?>
        <div class="alert-success">
            <?= htmlspecialchars($_SESSION['status']) ?>
        </div>
        <?php unset($_SESSION['status']); ?>
    <?php endif; ?>

    <?= $content ?>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>

<!-- Modal pour afficher les images -->
<div id="image-modal" hidden aria-hidden="true">
    <div class="modal-dialog" style="max-width:90%; max-height:90%; overflow:auto;">
        <img id="image-modal-img" src="" alt="" style="max-width:100%; height:auto;">
        <button id="image-modal-close" type="button" aria-label="Fermer" style="position:absolute; top:10px; right:10px; padding:8px 12px; background:#ccc; border:none; border-radius:4px; cursor:pointer;">✕</button>
    </div>
</div>

<style>
    #image-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        align-items: center;
        justify-content: center;
        z-index: 9999;
        display: none;
    }
    #image-modal:not([hidden]) {
        display: flex;
    }
    #image-modal[hidden] {
        display: none !important;
    }
    #image-modal .modal-dialog {
        position: relative;
        background: white;
        padding: 20px;
        border-radius: 8px;
    }
</style>

<script src="<?= htmlspecialchars($baseUrl . '/public/js/app.js') ?>"></script>
</body>
</html>
