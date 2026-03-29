<?php
/** @var array<int, string> $errors */
/** @var array<string, string> $old */
/** @var array<int, array{id_type_utilisateur:int, libelle:string}> $types */
/** @var int $userId */

$old = $old ?? [];
$errors = $errors ?? [];
?>
<div class="page-head">
    <h1 class="page-title">Éditer utilisateur #<?= (int) $userId ?></h1>
    <a href="<?= htmlspecialchars(($baseUrl ?? '') . '/users') ?>" class="btn btn-secondary">Retour</a>
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

<form method="post" action="<?= htmlspecialchars(($baseUrl ?? '') . '/users/edit?id=' . (int) $userId) ?>" class="form form-narrow">
    <input type="hidden" name="_token" value="<?= htmlspecialchars((string)($csrfToken ?? '')) ?>">
    <div class="form-grid">
        <label class="field">
            <span class="field-label">Nom *</span>
            <input class="input" type="text" name="nom" maxlength="50" required autocomplete="name" placeholder="Ex: Jean Dupont"
                   value="<?= htmlspecialchars($old['nom'] ?? '') ?>">
            <span class="help">50 caractères max.</span>
        </label>

        <label class="field">
            <span class="field-label">Email *</span>
            <input class="input" type="email" name="email" maxlength="100" required autocomplete="email" placeholder="exemple@email.com"
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>">
            <span class="help">100 caractères max.</span>
        </label>

        <label class="field">
            <span class="field-label">Type utilisateur *</span>
            <select class="select" name="id_type_utilisateur" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($types as $t): ?>
                    <?php $selected = ((string) ($old['id_type_utilisateur'] ?? '') === (string) (int) $t['id_type_utilisateur']); ?>
                    <option value="<?= (int) $t['id_type_utilisateur'] ?>" <?= $selected ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="field">
            <span class="field-label">Mot de passe (laisser vide pour ne pas changer)</span>
            <input class="input" type="password" name="mot_de_passe" maxlength="50" autocomplete="new-password" placeholder="••••••••" value="">
            <span class="help">50 caractères max. (schéma actuel: VARCHAR(50)).</span>
        </label>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </div>
</form>
