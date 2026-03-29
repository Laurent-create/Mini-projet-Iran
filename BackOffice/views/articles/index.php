<?php
$isAdmin = is_array(($currentUser ?? null)) && (int) (($currentUser['type'] ?? 0)) === 1;
$baseUrl = $baseUrl ?? '';
$articles = $articles ?? [];
$categories = $categories ?? [];
$statuses = $statuses ?? [];
$authors = $authors ?? [];
$filters = $filters ?? [];
?>
<div class="page-head">
    <h1 class="page-title">Articles</h1>
    <a href="<?= htmlspecialchars($baseUrl . '/articles/create') ?>" class="btn btn-primary btn-icon" title="Nouvel article" aria-label="Nouvel article">
        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
</div>

<form method="get" action="<?= htmlspecialchars($baseUrl . '/articles') ?>" class="filters">
    <label class="field">
        <span class="field-label">Recherche</span>
        <input class="input" type="text" name="q" value="<?= htmlspecialchars($filters['q'] ?? '') ?>" placeholder="titre ou slug" style="min-width: 220px;">
    </label>

    <label class="field">
        <span class="field-label">Catégorie</span>
        <select class="select" name="category" style="min-width: 190px;">
            <option value="">Toutes</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= (int) ($cat['id'] ?? 0) ?>" <?= ((string) ($filters['category'] ?? '') === (string) ($cat['id'] ?? '')) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['libelle'] ?? '') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label class="field">
        <span class="field-label">Statut</span>
        <select class="select" name="status" style="min-width: 170px;">
            <option value="">Tous</option>
            <?php foreach ($statuses as $st): ?>
                <option value="<?= (int) ($st['id'] ?? 0) ?>" <?= ((string) ($filters['status'] ?? '') === (string) ($st['id'] ?? '')) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($st['libelle'] ?? '') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <?php if ($isAdmin): ?>
        <label class="field">
            <span class="field-label">Auteur</span>
            <select class="select" name="author" style="min-width: 220px;">
                <option value="">Tous</option>
                <?php foreach ($authors as $a): ?>
                    <option value="<?= (int) ($a['id'] ?? 0) ?>" <?= ((string) ($filters['author'] ?? '') === (string) ($a['id'] ?? '')) ? 'selected' : '' ?>>
                        <?= htmlspecialchars(($a['nom'] ?? '') . ' (' . ($a['email'] ?? '') . ')') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary">
        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M3 5h18l-7 8v6l-4-2v-4L3 5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Filtrer
    </button>
    <a href="<?= htmlspecialchars($baseUrl . '/articles') ?>" class="btn btn-secondary">
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
                <th>#</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th>Créé</th>
                <th>Publié</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?= (int) ($article['id_article'] ?? 0) ?></td>
                    <td>
                        <div class="cell-strong"><?= htmlspecialchars($article['titre'] ?? '') ?></div>
                        <div class="cell-muted">
                            Description: <?= htmlspecialchars(mb_substr((string) ($article['meta_description'] ?? ''), 0, 120) . (mb_strlen((string) ($article['meta_description'] ?? '')) > 120 ? '…' : '')) ?>
                        </div>
                        <?php if (!empty($article['image_principale'])): ?>
                            <div class="cell-muted">
                                <button type="button" class="image-popup-trigger"
                                        data-image-popup
                                        data-image-src="/storage/<?= htmlspecialchars($article['image_principale']) ?>"
                                        data-image-alt="Image principale de l'article">
                                    Voir image
                                </button>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($article['categorie_libelle'] ?? '') ?></td>
                    <td><?= htmlspecialchars($article['statu_libelle'] ?? '') ?></td>
                    <td><?= isset($article['date_creation']) ? date('Y-m-d', strtotime($article['date_creation'])) : '' ?></td>
                    <td>
                        <?= isset($article['date_publication']) && $article['date_publication'] ? date('Y-m-d', strtotime($article['date_publication'])) : '' ?>
                    </td>
                    <td>
                        <div class="actions">
                            <a href="<?= htmlspecialchars($baseUrl . '/articles/edit?id=' . (int) ($article['id_article'] ?? 0)) ?>" class="btn btn-secondary btn-icon" title="Éditer" aria-label="Éditer">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M3 21v-3.75L17.81 2.44a2 2 0 0 1 2.83 0l0 0a2 2 0 0 1 0 2.83L5.83 20.08 3 21z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>

                            <?php if (in_array((int) ($article['id_article_statu'] ?? 0), [1, 3], true)): ?>
                                <form method="post" action="<?= htmlspecialchars($baseUrl . '/articles/publish?id=' . (int) ($article['id_article'] ?? 0)) ?>" style="display:inline;" onsubmit="return confirm('Publier cet article maintenant ?');"><input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
                                    <button type="submit" class="btn btn-primary btn-icon" title="Publier" aria-label="Publier">
                                        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M7 10l5-5 5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M12 5v14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </form>
                            <?php endif; ?>

                            <?php if ((int) ($article['id_article_statu'] ?? 0) !== 3): ?>
                                <form method="post" action="<?= htmlspecialchars($baseUrl . '/articles/archive?id=' . (int) ($article['id_article'] ?? 0)) ?>" style="display:inline;" onsubmit="return confirm('Archiver cet article ?');"><input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
                                    <button type="submit" class="btn btn-secondary btn-icon" title="Archiver" aria-label="Archiver">
                                        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M3 7h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M21 7v13a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M10 11h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </form>
                            <?php endif; ?>

                            <form method="post" action="<?= htmlspecialchars($baseUrl . '/articles/destroy?id=' . (int) ($article['id_article'] ?? 0)) ?>" style="display:inline;" onsubmit="return confirm('Supprimer cet article ? Cette action est irréversible.');"><input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
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
