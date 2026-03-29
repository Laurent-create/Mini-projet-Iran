<?php
/** @var array<int, App\Models\UserModel> $users */
/** @var array<int, array{id_type_utilisateur:int, libelle:string}> $types */

$typeMap = [];
foreach (($types ?? []) as $t) {
    $typeMap[(int) $t['id_type_utilisateur']] = (string) $t['libelle'];
}
?>
<div class="page-head">
    <h1 class="page-title">Utilisateurs</h1>
    <a href="<?= htmlspecialchars(($baseUrl ?? '') . '/users/create') ?>" class="btn btn-primary">Nouvel utilisateur</a>
</div>

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
                        <a class="btn btn-secondary" href="<?= htmlspecialchars(($baseUrl ?? '') . '/users/show?id=' . (int) $u->id_utilisateur) ?>">Voir</a>
                        <a class="btn btn-secondary" href="<?= htmlspecialchars(($baseUrl ?? '') . '/users/edit?id=' . (int) $u->id_utilisateur) ?>">Éditer</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
