<?php
$baseUrl = $baseUrl ?? '';
$categories = $categories ?? [];
$isEdit = !empty($article);
$article = $article ?? [];
$old = $old ?? [];

// Helper function to get old value or fallback to article value for form repopulation on errors
function old(string $field, array $oldData, array $articleData, $default = '') {
    return !empty($oldData) ? ($oldData[$field] ?? $default) : ($articleData[$field] ?? $default);
}
?>
<div class="form-two-col">
    <div class="form-grid">
        <label class="field">
            <span class="field-label">Titre *</span>
            <input type="text" name="titre" value="<?= htmlspecialchars(old('titre', $old, $article)) ?>" required maxlength="250" class="input">
            <span class="help">Le slug + les metas SEO sont générés automatiquement.</span>
        </label>

        <label class="field">
            <span class="field-label">Catégorie *</span>
            <select name="id_article_categorie" required class="select">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= (int) ($cat['id'] ?? 0) ?>" <?= ((int) old('id_article_categorie', $old, $article) === (int) ($cat['id'] ?? 0)) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['libelle'] ?? '') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="field">
            <span class="field-label">Description (résumé)</span>
            <textarea name="description" rows="3" maxlength="1000" class="textarea"><?= htmlspecialchars(old('description', $old, $article)) ?></textarea>
            <span class="help">Utilisée pour générer automatiquement la meta description SEO (~155 caractères).</span>
        </label>

        <label class="field">
            <span class="field-label">Image principale</span>
            <input type="file" name="image_principale" accept="image/png,image/jpeg,image/webp">
            <?php if ($isEdit && !empty($article['image_principale'])): ?>
                <button type="button" class="image-popup-trigger help"
                        data-image-popup
                        data-image-src="/storage/<?= htmlspecialchars($article['image_principale']) ?>"
                        data-image-alt="Image principale actuelle">
                    Voir l'image actuelle
                </button>
            <?php endif; ?>
        </label>
    </div>

    <div class="form-grid">
        <label class="field">
            <span class="field-label">Contenu</span>
            <textarea id="contenu" name="contenu" rows="12" class="textarea textarea-tall"><?= htmlspecialchars(old('contenu', $old, $article)) ?></textarea>
        </label>
    </div>
</div>
