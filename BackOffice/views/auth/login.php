<?php
/** @var array<int, string> $errors */
/** @var array<string, string> $old */

$old = $old ?? [];
$errors = $errors ?? [];
?>
<div class="page-head">
    <h1 class="page-title">Connexion</h1>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert-error" style="margin-top:16px;">
        <strong>Erreurs</strong>
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= htmlspecialchars(($baseUrl ?? '') . '/login') ?>" class="form form-narrow">
    <input type="hidden" name="_token" value="<?= htmlspecialchars((string)($csrfToken ?? '')) ?>">

    <div class="form-grid">
        <label class="field">
            <span class="field-label">Email *</span>
            <input class="input" type="email" name="email" maxlength="100" required autocomplete="email" placeholder="exemple@email.com"
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>">
        </label>

        <label class="field">
            <span class="field-label">Mot de passe *</span>
            <input class="input" type="password" name="mot_de_passe" maxlength="50" required autocomplete="current-password" placeholder="••••••••" value="">
        </label>

        <button type="submit" class="btn btn-primary">Se connecter</button>

        <div class="help">
            Pas de compte ? <a href="<?= htmlspecialchars(($baseUrl ?? '') . '/register') ?>">Créer un compte</a>
        </div>
    </div>
</form>
