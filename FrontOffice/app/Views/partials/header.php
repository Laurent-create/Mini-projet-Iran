<?php
declare(strict_types=1);

$title = isset($title) ? (string) $title : 'Iran Focus';
$categories = isset($categories) && is_array($categories) ? $categories : [];
$search = isset($search) ? (string) $search : '';
$categoryId = isset($categoryId) ? (int) $categoryId : 0;
$logoPath = '/assets/images/iran_focus_logo.png';
$logoPathOptimized = '/media/resize/logo' . $logoPath;
$logoPathOptimized2x = '/media/resize/logo2x' . $logoPath;
$metaDescription = isset($metaDescription) ? (string) $metaDescription : 'Iran Focus - analyses geopolitique et actualites sur l\'Iran.';
$robotsMeta = isset($robotsMeta) ? (string) $robotsMeta : 'index,follow';
$canonicalPath = isset($canonicalPath) ? (string) $canonicalPath : (string) (parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/articles'), PHP_URL_PATH) ?? '/articles');
$structuredData = isset($structuredData) && is_array($structuredData) ? $structuredData : [];

$appConfig = require dirname(__DIR__, 3) . '/config/config.php';
$appUrl = rtrim((string) ($appConfig['app_url'] ?? ''), '/');
if ($appUrl === '') {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
    $appUrl = $scheme . '://' . $host;
}

$canonicalUrl = $appUrl . '/' . ltrim($canonicalPath, '/');

$indexUrl = '/articles';
$allUrl = $indexUrl;
if ($search !== '') {
    $allUrl .= '?q=' . urlencode($search);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="robots" content="<?= htmlspecialchars($robotsMeta, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="alternate" hreflang="fr" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">

    <?php foreach ($structuredData as $schema): ?>
        <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
    <?php endforeach; ?>
</head>
<body>
    <header class="site-header">
        <div class="container nav-top">
            <a class="brand" href="/articles">
                <img class="brand-logo" src="<?= htmlspecialchars($logoPathOptimized, ENT_QUOTES, 'UTF-8') ?>" srcset="<?= htmlspecialchars($logoPathOptimized, ENT_QUOTES, 'UTF-8') ?> 1x, <?= htmlspecialchars($logoPathOptimized2x, ENT_QUOTES, 'UTF-8') ?> 2x" sizes="52px" alt="Iran Focus logo" width="52" height="52" loading="eager" fetchpriority="high" decoding="async">
                <span class="brand-copy">
                    <strong>Iran Focus</strong>
                    <small>Political Analysis & News</small>
                </span>
            </a>

            <form class="search-form" action="/articles" method="GET">
                <input
                    type="search"
                    name="q"
                    value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Search in latest reports"
                    aria-label="Search articles"
                >
                <?php if ($categoryId > 0): ?>
                    <input type="hidden" name="category" value="<?= $categoryId ?>">
                <?php endif; ?>
                <button type="submit">Search</button>
            </form>
        </div>

        <nav class="category-nav">
            <div class="container category-list">
                <a class="category-link <?= $categoryId === 0 ? 'is-active' : '' ?>" href="<?= htmlspecialchars($allUrl, ENT_QUOTES, 'UTF-8') ?>">All</a>
                <?php foreach ($categories as $category): ?>
                    <?php
                        $loopCategoryId = (int) ($category['id_article_categorie'] ?? 0);
                        $query = ['category' => $loopCategoryId];
                        if ($search !== '') {
                            $query['q'] = $search;
                        }
                        $url = '/articles?' . http_build_query($query);
                    ?>
                    <a class="category-link <?= $loopCategoryId === $categoryId ? 'is-active' : '' ?>" href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars((string) ($category['libelle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </nav>
    </header>

    <main class="container page-content">
