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

$coverImage = $resolveImageUrl((string) ($article['image_principale'] ?? ''));
$publicationDate = $formatDateTime($article['date_publication'] ?? null);

require __DIR__ . '/../partials/header.php';
?>

<article class="article-detail">
    <a class="back-link" href="/articles?category=<?= (int) ($article['id_article_categorie'] ?? 0) ?>">Back to list</a>

    <header class="detail-header">
        <p class="eyebrow"><?= htmlspecialchars((string) ($article['category_label'] ?? 'Article'), ENT_QUOTES, 'UTF-8') ?></p>
        <h1><?= htmlspecialchars((string) ($article['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="detail-meta">
            Published <?= htmlspecialchars($publicationDate, ENT_QUOTES, 'UTF-8') ?>
            <?php if (!empty($article['author_name'])): ?>
                | By <?= htmlspecialchars((string) $article['author_name'], ENT_QUOTES, 'UTF-8') ?>
            <?php endif; ?>
        </p>
        <?php if (!empty($article['meta_description'])): ?>
            <p class="detail-summary"><?= htmlspecialchars((string) $article['meta_description'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
    </header>

    <figure class="cover-image">
        <img src="<?= htmlspecialchars($coverImage, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) ($article['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
    </figure>

    <div class="prose">
        <?= (string) ($article['contenu'] ?? '') ?>
    </div>

    <?php if (isset($images) && count($images) > 0): ?>
        <section class="gallery">
            <h2>Gallery</h2>
            <div class="gallery-grid">
                <?php foreach ($images as $image): ?>
                    <?php $galleryUrl = $resolveImageUrl((string) ($image['url'] ?? '')); ?>
                    <figure class="gallery-item">
                        <img src="<?= htmlspecialchars($galleryUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) (($image['legend'] ?? '') !== '' ? $image['legend'] : ($article['titre'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">
                        <?php if (!empty($image['legend'])): ?>
                            <figcaption><?= htmlspecialchars((string) $image['legend'], ENT_QUOTES, 'UTF-8') ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</article>

<?php require __DIR__ . '/../partials/footer.php'; ?>
