<?php
/** @var array<int, string> $errors */
/** @var array<string, string> $old */

$old = $old ?? [];
$errors = $errors ?? [];
?>
<div class="page-head">
    <h1 class="page-title">Inscription</h1>
    <a href="<?= htmlspecialchars(($baseUrl ?? '') . '/login') ?>" class="btn btn-secondary">Retour</a>
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

<form method="post" action="<?= htmlspecialchars(($baseUrl ?? '') . '/register') ?>" class="form form-narrow">
    <input type="hidden" name="_token" value="<?= htmlspecialchars((string)($csrfToken ?? '')) ?>">

    <div class="form-grid">
        <label class="field">
            <span class="field-label">Nom *</span>
            <input class="input" type="text" name="nom" maxlength="50" required autocomplete="name" placeholder="Ex: Jean Dupont"
                   value="<?= htmlspecialchars($old['nom'] ?? '') ?>">
        </label>

        <label class="field">
            <span class="field-label">Email *</span>
            <input class="input" type="email" name="email" maxlength="100" required autocomplete="email" placeholder="exemple@email.com"
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>">
        </label>

        <label class="field">
            <span class="field-label">Mot de passe *</span>
            <input class="input" type="password" name="mot_de_passe" maxlength="50" required autocomplete="new-password" placeholder="••••••••" value="">
            <span class="help">50 caractères max. (schéma actuel: VARCHAR(50)).</span>
        </label>

        <button type="submit" class="btn btn-primary">Créer le compte</button>

        <div class="help">
            En créant un compte, vous serez <strong>Rédacteur</strong> par défaut.
        </div>
    </div>
</form>
