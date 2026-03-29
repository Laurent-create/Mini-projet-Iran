<?php
$baseUrl = $baseUrl ?? '';
$categories = $categories ?? [];
$filters = $filters ?? [];
?>
<div class="page-head">
    <h1 class="page-title">Catégories</h1>
    <a href="<?= htmlspecialchars($baseUrl . '/categories/create') ?>" class="btn btn-primary" title="Nouvelle catégorie">
        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Nouvelle catégorie
    </a>
</div>

<div class="table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Libellé</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td class="cell-strong">#<?= (int) ($cat['id'] ?? 0) ?></td>
                    <td><?= htmlspecialchars($cat['libelle'] ?? '') ?></td>
                    <td>
                        <div class="actions">
                            <a href="<?= htmlspecialchars($baseUrl . '/categories/edit?id=' . (int) ($cat['id'] ?? 0)) ?>" class="btn btn-secondary btn-icon" title="Éditer" aria-label="Éditer">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M3 21v-3.75L17.81 2.44a2 2 0 0 1 2.83 0l0 0a2 2 0 0 1 0 2.83L5.83 20.08 3 21z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>

                            <form method="post" action="<?= htmlspecialchars($baseUrl . '/categories/destroy?id=' . (int) ($cat['id'] ?? 0)) ?>" style="display:inline;" onsubmit="return confirm('Supprimer cette catégorie ? Cette action est irréversible.');"><input type="hidden" name="_token" value="<?= htmlspecialchars(($csrfToken ?? '')) ?>">
                                <button type="submit" class="btn btn-danger btn-icon" title="Supprimer" aria-label="Supprimer">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M3 6h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M8 6V4h8v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6 6l1 16h10l1-16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10 11v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M14 11v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
