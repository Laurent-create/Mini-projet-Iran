<?php
$baseUrl = $baseUrl ?? '';
$currentUser = $currentUser ?? null;
$csrfToken = $csrfToken ?? '';
$isLoggedIn = is_array($currentUser) && !empty($currentUser['id']);
$isAdmin = $isLoggedIn && (int)($currentUser['type'] ?? 0) === 1;
$homeHref = $isLoggedIn ? ($baseUrl . '/dashboard') : ($baseUrl . '/login');
?>
<div class="navbar">
    <div class="navbar-container">
        <div class="navbar-left">
            <a href="<?= htmlspecialchars($homeHref) ?>" class="navbar-brand">Studio d'écriture</a>

            <?php if ($isLoggedIn): ?>
                <span class="navbar-divider">|</span>
                <a href="<?= htmlspecialchars($baseUrl . '/dashboard') ?>" class="navbar-link">Dashboard</a>
                <?php if ($isAdmin): ?>
                    <a href="<?= htmlspecialchars($baseUrl . '/users') ?>" class="navbar-link">Utilisateurs</a>
                <?php endif; ?>
            <?php else: ?>
                <span class="navbar-divider">|</span>
                <a href="<?= htmlspecialchars($baseUrl . '/login') ?>" class="navbar-link">Connexion</a>
                <a href="<?= htmlspecialchars($baseUrl . '/register') ?>" class="navbar-link">Inscription</a>
            <?php endif; ?>
        </div>
        <div class="navbar-right">
            <?php if ($isLoggedIn): ?>
                <span class="user-email"><?= htmlspecialchars((string)$currentUser['email']) ?></span>
                <form method="post" action="<?= htmlspecialchars($baseUrl . '/logout') ?>" style="margin:0;">
                    <input type="hidden" name="_token" value="<?= htmlspecialchars((string)$csrfToken) ?>">
                    <button type="submit" class="logout-btn">Déconnexion</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
