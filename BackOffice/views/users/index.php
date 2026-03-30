<?php
/** @var array<int, App\Models\UserModel> $users */
/** @var array<int, array{id_type_utilisateur:int, libelle:string}> $types */
/** @var array<string, mixed> $filters */
/** @var array<string, mixed> $pagination */

$typeMap = [];
foreach (($types ?? []) as $t) {
    $typeMap[(int) $t['id_type_utilisateur']] = (string) $t['libelle'];
}

$baseUrl = $baseUrl ?? '';
?>
<div class="page-head">
    <h1 class="page-title">Utilisateurs</h1>
    <a href="<?= htmlspecialchars($baseUrl . '/users/create') ?>" class="btn btn-primary btn-icon" title="Nouvel utilisateur" aria-label="Nouvel utilisateur">
        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
</div>

<form method="get" action="<?= htmlspecialchars($baseUrl . '/users') ?>" class="filters">
    <label class="field">
        <span class="field-label">Recherche</span>
        <input class="input" type="text" name="q" value="<?= htmlspecialchars($filters['q'] ?? '') ?>" placeholder="nom ou email" style="min-width: 220px;">
    </label>

    <label class="field">
        <span class="field-label">Rôle</span>
        <select class="select" name="type" style="min-width: 190px;">
            <option value="">Tous</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= (int) ($t['id_type_utilisateur'] ?? 0) ?>" <?= ((int) ($filters['type'] ?? 0) === (int) ($t['id_type_utilisateur'] ?? 0)) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['libelle'] ?? '') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <button type="submit" class="btn btn-primary">
        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M3 5h18l-7 8v6l-4-2v-4L3 5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Filtrer
    </button>
    <a href="<?= htmlspecialchars($baseUrl . '/users') ?>" class="btn btn-secondary">
        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M21 12a9 9 0 1 1-3-6.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M21 3v6h-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Réinitialiser
    </a>
</form>

<div class="table-wrap">
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td class="cell-strong">#<?= (int) $u->id_utilisateur ?></td>
                <td><?= htmlspecialchars($u->nom) ?></td>
                <td><?= htmlspecialchars($u->email) ?></td>
                <td><?= htmlspecialchars($typeMap[(int) $u->id_type_utilisateur] ?? ('Type #' . (int) $u->id_type_utilisateur)) ?></td>
                <td>
                    <div class="actions">
                        <a class="btn btn-secondary" href="<?= htmlspecialchars($baseUrl . '/users/show?id=' . (int) $u->id_utilisateur) ?>">Voir</a>
                        <a class="btn btn-secondary" href="<?= htmlspecialchars($baseUrl . '/users/edit?id=' . (int) $u->id_utilisateur) ?>">Éditer</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($pagination['pages'] > 1): ?>
    <div class="pagination" style="margin-top: 20px; display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; align-items: center;">
        <?php if ($pagination['page'] > 1): ?>
            <a href="<?= htmlspecialchars($baseUrl . '/users?q=' . urlencode($filters['q']) . '&type=' . (int) $filters['type'] . '&page=1') ?>" class="btn btn-secondary btn-sm">« Première</a>
            <a href="<?= htmlspecialchars($baseUrl . '/users?q=' . urlencode($filters['q']) . '&type=' . (int) $filters['type'] . '&page=' . ($pagination['page'] - 1)) ?>" class="btn btn-secondary btn-sm">‹ Précédente</a>
        <?php endif; ?>

        <?php 
            $startPage = max(1, $pagination['page'] - 2);
            $endPage = min($pagination['pages'], $pagination['page'] + 2);
            if ($startPage > 1): ?>
                <span style="padding: 8px;">...</span>
            <?php endif; ?>

            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <?php if ($i === $pagination['page']): ?>
                    <span style="padding: 8px 12px; background: #0066cc; color: white; border-radius: 4px;"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($baseUrl . '/users?q=' . urlencode($filters['q']) . '&type=' . (int) $filters['type'] . '&page=' . $i) ?>" class="btn btn-secondary btn-sm"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($endPage < $pagination['pages']): ?>
                <span style="padding: 8px;">...</span>
            <?php endif; ?>

        <?php if ($pagination['page'] < $pagination['pages']): ?>
            <a href="<?= htmlspecialchars($baseUrl . '/users?q=' . urlencode($filters['q']) . '&type=' . (int) $filters['type'] . '&page=' . ($pagination['page'] + 1)) ?>" class="btn btn-secondary btn-sm">Suivante ›</a>
            <a href="<?= htmlspecialchars($baseUrl . '/users?q=' . urlencode($filters['q']) . '&type=' . (int) $filters['type'] . '&page=' . $pagination['pages']) ?>" class="btn btn-secondary btn-sm">Dernière »</a>
        <?php endif; ?>

        <span style="padding: 8px; color: #666;">Page <?= $pagination['page'] ?> / <?= $pagination['pages'] ?></span>
    </div>
<?php endif; ?>
