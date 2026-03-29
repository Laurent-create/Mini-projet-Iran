<div class="page-head">
    <h1 class="page-title">Dashboard</h1>
</div>

<div class="card" style="margin-top:16px;">
    <div class="card-body">
        <p class="help" style="margin:0;">
            Accès rapide :
            <?php if (is_array(($currentUser ?? null)) && (int) (($currentUser['type'] ?? 0)) === 1): ?>
                <a href="<?= htmlspecialchars(($baseUrl ?? '') . '/users') ?>">Utilisateurs</a>
            <?php else: ?>
                <span>Fonctionnalités en cours d’ajout.</span>
            <?php endif; ?>
        </p>
    </div>
</div>
