<?php
$baseUrl = $baseUrl ?? '';
$categories = $categories ?? [];
?>
<div class="page-head">
    <h1 class="page-title">Nouvel article</h1>
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

<form method="post" action="<?= htmlspecialchars($baseUrl . '/articles/create') ?>" enctype="multipart/form-data" class="form">
    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

    <?php include '_form.php'; ?>

    <button type="submit" class="btn btn-primary">
        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M12 5v14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M5 12h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        Créer (brouillon)
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
                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());

                    fetch('<?= htmlspecialchars($baseUrl . '/articles/upload-tinymce') ?>', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                        },
                        credentials: 'same-origin',
                        body: formData,
                    })
                        .then(async (res) => {
                            if (!res.ok) {
                                throw new Error('Upload failed');
                            }
                            return res.json();
                        })
                        .then((json) => {
                            if (json && typeof json.location === 'string') {
                                resolve(json.location);
                            } else {
                                reject('Invalid upload response');
                            }
                        })
                        .catch((err) => reject(err?.message || 'Upload failed'));
                });
            },
        });
    }
</script>
