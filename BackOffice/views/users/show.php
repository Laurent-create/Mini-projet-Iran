<?php
/** @var App\Models\UserModel $user */
/** @var string|null $typeLabel */
?>
<div class="page-head">
    <h1 class="page-title">Utilisateur #<?= (int) $user->id_utilisateur ?></h1>
    <div class="actions">
        <a href="<?= htmlspecialchars(($baseUrl ?? '') . '/users') ?>" class="btn btn-secondary">Retour</a>
        <a href="<?= htmlspecialchars(($baseUrl ?? '') . '/users/edit?id=' . (int) $user->id_utilisateur) ?>" class="btn btn-primary">Éditer</a>
    </div>
</div>

<div class="table-wrap">
    <table class="table">
        <tbody>
        <tr>
            <th style="width:260px;">Nom</th>
            <td><?= htmlspecialchars($user->nom) ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= htmlspecialchars($user->email) ?></td>
        </tr>
        <tr>
            <th>Rôle</th>
            <td><?= htmlspecialchars($typeLabel ?? ('Type #' . (int) $user->id_type_utilisateur)) ?></td>
        </tr>
        </tbody>
    </table>
</div>
