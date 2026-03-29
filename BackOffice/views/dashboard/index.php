<style>
    .dashboard-container {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        background: white;
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .dashboard-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        padding: 48px 40px;
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '✍️';
        position: absolute;
        font-size: 200px;
        opacity: 0.05;
        bottom: -40px;
        right: -40px;
        pointer-events: none;
    }

    .brand {
        position: relative;
        z-index: 1;
    }

    .brand h2 {
        color: white;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }

    .brand p {
        color: #94a3b8;
        font-size: 16px;
        line-height: 1.5;
    }

    .dashboard-content {
        padding: 48px 48px;
        background: white;
    }

    .info-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 32px;
    }

    .info-card h3 {
        font-size: 20px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 16px;
    }

    .user-info {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        margin-top: 16px;
    }

    .user-info p {
        color: #334155;
        font-size: 15px;
        line-height: 1.6;
        margin-bottom: 12px;
    }

    .user-info p:last-child {
        margin-bottom: 0;
    }

    .user-info strong {
        color: #0f172a;
        font-weight: 600;
    }

    .user-email {
        background: #f1f5f9;
        padding: 12px 16px;
        border-radius: 12px;
        font-family: monospace;
        font-size: 14px;
        color: #1e293b;
        margin-top: 12px;
    }

    .action-buttons {
        display: flex;
        gap: 16px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn .icon {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        display: inline-block;
    }

    .btn-primary {
        background: #764ba2;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(118, 75, 162, 0.3);
        text-decoration: none;
        color: white;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        text-decoration: none;
        color: #1e293b;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            padding: 32px 28px;
            text-align: center;
        }

        .dashboard-content {
            padding: 36px 28px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            text-align: center;
            justify-content: center;
        }
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="brand">
            <h2>Studio d'écriture</h2>
            <p>Plateforme de création d'articles et de contenus éditoriaux</p>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="info-card">
            <h3>Bienvenue</h3>
            <div class="user-info">
                <p><strong>Informations de connexion</strong></p>
                <div class="user-email">
                    <?= htmlspecialchars($currentUser['email'] ?? 'user@example.com', ENT_QUOTES) ?>
                </div>
                <p style="margin-top: 16px;">
                    Vous êtes connecté en tant que <strong><?= htmlspecialchars($currentUser['email'] ?? 'user@example.com', ENT_QUOTES) ?></strong>
                </p>
            </div>

            <div class="action-buttons">
                <a href="<?= htmlspecialchars($baseUrl . '/articles/create') ?>" class="btn btn-primary">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M12 5v14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M5 12h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Créer un article
                </a>
                <a href="<?= htmlspecialchars($baseUrl . '/articles') ?>" class="btn btn-secondary">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M8 6h13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M8 12h13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M8 18h13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M3 6h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        <path d="M3 12h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        <path d="M3 18h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    Mes articles
                </a>
                <?php if (is_array($currentUser) && (int)($currentUser['type'] ?? 0) === 1): ?>
                    <a href="<?= htmlspecialchars($baseUrl . '/categories') ?>" class="btn btn-secondary">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M6 9h12M6 15h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        Catégories
                    </a>
                    <a href="<?= htmlspecialchars($baseUrl . '/users') ?>" class="btn btn-secondary">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M18 18c0-2 -1.5-4-6-4s-6 2-6 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        Utilisateurs
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
