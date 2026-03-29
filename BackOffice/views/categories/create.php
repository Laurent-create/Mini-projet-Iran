<?php
$baseUrl = $baseUrl ?? '';
$errors = $errors ?? [];
$old = $old ?? [];
?>
<div class="page-head">
    <h1 class="page-title">Nouvelle catégorie</h1>
    <a href="<?= htmlspecialchars($baseUrl . '/categories') ?>" class="btn btn-secondary">Retour</a>
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

<form method="post" action="<?= htmlspecialchars($baseUrl . '/categories/create') ?>" class="form form-narrow">
    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
    <div class="form-grid">
        <label class="field">
            <span class="field-label">Libellé *</span>
            <input class="input" type="text" name="libelle" maxlength="50" required autocomplete="off" placeholder="Ex: Science-fiction"
                   value="<?= htmlspecialchars($old['libelle'] ?? '') ?>">
        </label>

        <button type="submit" class="btn btn-primary">Créer</button>
    </div>
</form>
