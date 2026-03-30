<?php
$baseUrl = $baseUrl ?? '';
$categories = $categories ?? [];
$filters = $filters ?? [];
$pagination = $pagination ?? ['page' => 1, 'pages' => 1, 'total' => 0, 'perPage' => 10];
$csrfToken = $csrfToken ?? '';
?>
<div class="page-head">
    <h1 class="page-title">Catégories</h1>
    <a href="<?= htmlspecialchars($baseUrl . '/categories/create') ?>" class="btn btn-primary btn-icon" title="Nouvel categorie" aria-label="Nouvel categorie">
        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
</div>

<div class="filters-row">
    <form method="GET" action="<?= htmlspecialchars($baseUrl . '/categories') ?>" class="filters">
        <label class="field">
            <span class="field-label">Recherche</span>
            <input 
                type="text" 
                name="q" 
                class="input" 
                placeholder="Rechercher par libellé..." 
                value="<?= htmlspecialchars((string) ($filters['q'] ?? '')) ?>"
                style="min-width: 220px;"
            >
        </label>

        <button type="submit" class="btn btn-primary">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                <path d="m21 21-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Filtrer
        </button>
        <a href="<?= htmlspecialchars($baseUrl . '/categories') ?>" class="btn btn-secondary">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M3 7v10a6 6 0 0 0 6 6h6a6 6 0 0 0 6-6V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M3 7h18M9 11h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            Réinitialiser
        </a>
    </form>
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
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">Aucune catégorie trouvée</td>
                </tr>
            <?php else: ?>
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

                                <form method="post" action="<?= htmlspecialchars($baseUrl . '/categories/destroy?id=' . (int) ($cat['id'] ?? 0)) ?>" style="display:inline;" onsubmit="return confirm('Supprimer cette catégorie ? Cette action est irréversible.');"><input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken) ?>">
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
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($pagination['pages'] > 1): ?>
<div class="pagination">
    <nav aria-label="Pagination">
        <div class="pagination-controls">
            <?php if ($pagination['page'] > 1): ?>
                <a href="<?= htmlspecialchars($baseUrl . '/categories?' . http_build_query(array_merge($filters, ['page' => 1]))) ?>" class="btn btn-outline btn-sm" title="Première page">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M18 5L6 12l12 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="<?= htmlspecialchars($baseUrl . '/categories?' . http_build_query(array_merge($filters, ['page' => $pagination['page'] - 1]))) ?>" class="btn btn-outline btn-sm" title="Page précédente">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            <?php endif; ?>

            <div class="pagination-numbers">
                <?php
                $currentPage = (int) ($pagination['page'] ?? 1);
                $totalPages = (int) ($pagination['pages'] ?? 1);
                $maxVisible = 5;

                $start = max(1, $currentPage - floor($maxVisible / 2));
                $end = min($totalPages, $start + $maxVisible - 1);
                if ($end - $start + 1 < $maxVisible) {
                    $start = max(1, $end - $maxVisible + 1);
                }

                if ($start > 1):
                    ?>
                    <a href="<?= htmlspecialchars($baseUrl . '/categories?' . http_build_query(array_merge($filters, ['page' => 1]))) ?>" class="btn btn-outline btn-sm">1</a>
                    <?php if ($start > 2): ?>
                        <span class="pagination-ellipsis">...</span>
                    <?php endif; ?>
                <?php endif;

                for ($page = $start; $page <= $end; $page++):
                    if ($page === $currentPage):
                        ?>
                        <span class="pagination-current"><?= $page ?></span>
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($baseUrl . '/categories?' . http_build_query(array_merge($filters, ['page' => $page]))) ?>" class="btn btn-outline btn-sm"><?= $page ?></a>
                    <?php endif;
                endfor;

                if ($end < $totalPages):
                    if ($end < $totalPages - 1):
                        ?>
                        <span class="pagination-ellipsis">...</span>
                    <?php endif; ?>
                    <a href="<?= htmlspecialchars($baseUrl . '/categories?' . http_build_query(array_merge($filters, ['page' => $totalPages]))) ?>" class="btn btn-outline btn-sm"><?= $totalPages ?></a>
                <?php endif; ?>
            </div>

            <?php if ($pagination['page'] < $pagination['pages']): ?>
                <a href="<?= htmlspecialchars($baseUrl . '/categories?' . http_build_query(array_merge($filters, ['page' => $pagination['page'] + 1]))) ?>" class="btn btn-outline btn-sm" title="Page suivante">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="<?= htmlspecialchars($baseUrl . '/categories?' . http_build_query(array_merge($filters, ['page' => $pagination['pages']]))) ?>" class="btn btn-outline btn-sm" title="Dernière page">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M6 5l12 7-12 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            <?php endif; ?>
        </div>

        <div class="pagination-info">
            Page <?= $pagination['page'] ?> / <?= $pagination['pages'] ?>
            (<?= $pagination['total'] ?> catégories)
        </div>
    </nav>
</div>
<?php endif; ?>
