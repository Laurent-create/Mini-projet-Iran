<?php
declare(strict_types=1);

$resolveImageUrl = static function (string $path): string {
    if ($path === '') {
        return '/assets/images/iran_focus_logo.png';
    }

    if (preg_match('/^https?:\/\//i', $path) === 1) {
        return $path;
    }

    return '/' . ltrim($path, '/');
};

$formatDateTime = static function (?string $rawDate): string {
    if ($rawDate === null || trim($rawDate) === '') {
        return '';
    }

    $timestamp = strtotime($rawDate);
    return $timestamp === false ? '' : date('d/m/Y H:i', $timestamp);
};

$truncate = static function (string $text, int $limit): string {
    if (strlen($text) <= $limit) {
        return $text;
    }

    return rtrim(substr($text, 0, $limit)) . '...';
};

$buildIndexUrl = static function (array $params = []): string {
    $params = array_filter($params, static fn ($value): bool => $value !== '' && $value !== null && $value !== 0);
    if ($params === []) {
        return '/articles';
    }

    return '/articles?' . http_build_query($params);
};

require __DIR__ . '/../partials/header.php';
?>

<?php if (isset($featuredArticle) && is_array($featuredArticle)): ?>
    <?php
        $featuredImage = $resolveImageUrl((string) ($featuredArticle['image_principale'] ?? ''));
        $featuredDate = $formatDateTime($featuredArticle['date_publication'] ?? null);
        $featuredContent = strip_tags((string) ($featuredArticle['contenu'] ?? ''));
        $featuredExcerpt = $truncate($featuredContent, 260);
        $featuredSlug = (string) ($featuredArticle['slug'] ?? '');
    ?>

    <section class="featured-article">
        <a class="featured-image" href="/articles/<?= rawurlencode($featuredSlug) ?>">
            <img src="<?= htmlspecialchars($featuredImage, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) ($featuredArticle['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </a>

        <div class="featured-content">
            <p class="eyebrow">Main story</p>
            <p class="featured-meta">
                <span><?= htmlspecialchars((string) ($featuredArticle['category_label'] ?? 'Unclassified'), ENT_QUOTES, 'UTF-8') ?></span>
                <span><?= htmlspecialchars($featuredDate, ENT_QUOTES, 'UTF-8') ?></span>
            </p>
            <h1>
                <a href="/articles/<?= rawurlencode($featuredSlug) ?>"><?= htmlspecialchars((string) ($featuredArticle['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?></a>
            </h1>
            <p><?= htmlspecialchars($featuredExcerpt, ENT_QUOTES, 'UTF-8') ?></p>
            <a class="featured-link" href="/articles/<?= rawurlencode($featuredSlug) ?>">Read full report</a>
        </div>
    </section>
<?php endif; ?>

<?php if ((!isset($articles) || count($articles) === 0) && !isset($featuredArticle)): ?>
    <section class="empty-state">
        <h2>No article found</h2>
        <p>Try another keyword or remove category filter.</p>
    </section>
<?php elseif (isset($articles) && count($articles) > 0): ?>
    <section class="article-grid">
        <?php foreach ($articles as $article): ?>
            <?php
                $imageUrl = $resolveImageUrl((string) ($article['image_principale'] ?? ''));
                $excerpt = $truncate(strip_tags((string) ($article['contenu'] ?? '')), 180);
                $articleDate = $formatDateTime($article['date_publication'] ?? null);
                $articleSlug = (string) ($article['slug'] ?? '');
            ?>
            <article class="article-card">
                <a class="image-wrap" href="/articles/<?= rawurlencode($articleSlug) ?>">
                    <img src="<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) ($article['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </a>

                <div class="card-content">
                    <p class="meta-row">
                        <span><?= htmlspecialchars((string) ($article['category_label'] ?? 'Unclassified'), ENT_QUOTES, 'UTF-8') ?></span>
                        <span><?= htmlspecialchars($articleDate, ENT_QUOTES, 'UTF-8') ?></span>
                    </p>

                    <h2>
                        <a href="/articles/<?= rawurlencode($articleSlug) ?>"><?= htmlspecialchars((string) ($article['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?></a>
                    </h2>

                    <p><?= htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </section>

    <?php if (($totalPages ?? 1) > 1): ?>
        <?php
            $currentPage = (int) ($page ?? 1);
            $queryBase = [
                'q' => (string) ($search ?? ''),
                'category' => (int) ($categoryId ?? 0),
            ];
        ?>
        <nav class="pager" aria-label="Pagination">
            <?php if ($currentPage <= 1): ?>
                <span class="pager-link disabled">Previous</span>
            <?php else: ?>
                <a class="pager-link" href="<?= htmlspecialchars($buildIndexUrl($queryBase + ['page' => $currentPage - 1]), ENT_QUOTES, 'UTF-8') ?>">Previous</a>
            <?php endif; ?>

            <span class="pager-current">Page <?= $currentPage ?> / <?= (int) $totalPages ?></span>

            <?php if ($currentPage < (int) $totalPages): ?>
                <a class="pager-link" href="<?= htmlspecialchars($buildIndexUrl($queryBase + ['page' => $currentPage + 1]), ENT_QUOTES, 'UTF-8') ?>">Next</a>
            <?php else: ?>
                <span class="pager-link disabled">Next</span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
