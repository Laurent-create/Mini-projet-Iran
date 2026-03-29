<?php
$baseUrl = $baseUrl ?? '';
$categories = $categories ?? [];
$article = $article ?? [];
$csrfToken = $csrfToken ?? '';
$errors = $errors ?? [];
?>
<div class="page-head">
    <h1 class="page-title">Éditer article #<?= (int) ($article['id_article'] ?? 0) ?></h1>
    <a href="<?= htmlspecialchars($baseUrl . '/articles') ?>" class="btn btn-secondary">
        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Retour
    </a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert-error" style="margin-top:16px;">
        <strong>Erreurs</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div style="margin-top:16px; display:flex; gap: 8px; align-items:center; flex-wrap:wrap;">
    <?php if (in_array((int) ($article['id_article_statu'] ?? 0), [1, 3], true)): ?>
        <form method="post" action="<?= htmlspecialchars($baseUrl . '/articles/publish?id=' . (int) ($article['id_article'] ?? 0)) ?>" onsubmit="return confirm('Publier cet article maintenant ?');"><input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <button type="submit" class="btn btn-primary">
                <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M22 2L11 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M22 2l-7 20-4-9-9-4 20-7z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Publier
            </button>
        </form>
    <?php endif; ?>

    <?php if ((int) ($article['id_article_statu'] ?? 0) !== 3): ?>
        <form method="post" action="<?= htmlspecialchars($baseUrl . '/articles/archive?id=' . (int) ($article['id_article'] ?? 0)) ?>" onsubmit="return confirm('Archiver cet article ?');"><input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <button type="submit" class="btn btn-secondary">
                <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M4 7h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M6 7l1 14h10l1-14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 11h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Archiver
            </button>
        </form>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars($baseUrl . '/articles/destroy?id=' . (int) ($article['id_article'] ?? 0)) ?>" onsubmit="return confirm('Supprimer cet article ? Cette action est irréversible.');"><input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button type="submit" class="btn btn-danger">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M3 6h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M8 6V4h8v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6 6l1 16h10l1-16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10 11v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M14 11v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            Supprimer
        </button>
    </form>

    <div class="help" style="font-size: 13px;">
        Statut: <strong><?= htmlspecialchars($article['statu_libelle'] ?? '') ?></strong>
        <?php if (!empty($article['date_publication'])): ?>
            • Publié: <strong><?= date('Y-m-d', strtotime($article['date_publication'])) ?></strong>
        <?php endif; ?>
    </div>
</div>

<form method="post" action="<?= htmlspecialchars($baseUrl . '/articles/edit?id=' . (int) ($article['id_article'] ?? 0)) ?>" enctype="multipart/form-data" class="form">
    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken) ?>">

    <?php include '_form.php'; ?>

    <button type="submit" class="btn btn-primary">
        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M4 7a2 2 0 0 1 2-2h10l4 4v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M14 5v4h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8 21v-6h8v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Enregistrer
    </button>
</form>

<script src="<?= htmlspecialchars($baseUrl) ?>/public/js/tinymce/tinymce.min.js"></script>
<script>
    if (window.tinymce) {
        tinymce.init({
            license_key: 'gpl',
            selector: '#contenu',
            min_height: 620,
            menubar: true,
            convert_urls: false,
            relative_urls: false,
            browser_spellcheck: true,
            toolbar_mode: 'sliding',
            toolbar_sticky: true,
            plugins: 'link lists advlist accordion anchor autolink autoresize autosave charmap code codesample directionality emoticons fullscreen help image importcss insertdatetime media nonbreaking pagebreak preview quickbars save searchreplace table visualblocks visualchars wordcount',
            toolbar: 'save restoredraft | undo redo | blocks | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link anchor | image media table | insertdatetime | pagebreak | emoticons charmap | searchreplace visualblocks visualchars | fullscreen preview | code codesample | removeformat | help',
            quickbars_selection_toolbar: 'bold italic underline | quicklink h2 h3 blockquote',
            quickbars_insert_toolbar: 'quickimage quicktable',
            link_default_protocol: 'https',
            image_description: true,
            image_caption: true,
            automatic_uploads: true,
            images_upload_credentials: true,
            images_upload_handler: (blobInfo, progress) => {
                return new Promise((resolve, reject) => {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    console.log('🔐 CSRF Token:', csrf);
                    console.log('📝 File name:', blobInfo.filename());
                    
                    const formData = new FormData();
                    formData.append('_token', csrf);
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    console.log('📦 FormData prepared');

                    fetch('<?= htmlspecialchars($baseUrl . '/articles/upload-tinymce') ?>', {
                        method: 'POST',
                        credentials: 'same-origin',
                        body: formData,
                    })
                        .then(async (res) => {
                            console.log('📨 Response status:', res.status, res.statusText);
                            if (!res.ok) {
                                const text = await res.text();
                                console.error('❌ Response body:', text);
                                throw new Error('Upload failed (HTTP ' + res.status + ')');
                            }
                            return res.json();
                        })
                        .then((json) => {
                            console.log('✅ JSON response:', json);
                            if (json && typeof json.location === 'string') {
                                console.log('🎉 Upload success, location:', json.location);
                                resolve(json.location);
                            } else {
                                console.error('❌ Invalid response format:', json);
                                reject('Invalid upload response');
                            }
                        })
                        .catch((err) => {
                            console.error('⚠️ Error:', err);
                            reject(err?.message || 'Upload failed');
                        });
                });
            },
        });
    }
</script>
