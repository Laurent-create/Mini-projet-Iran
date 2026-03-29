<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BackOffice - Connexion | Plateforme de création d'articles</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            background: #764ba2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
        }

        /* Effet de fond abstrait pour rappeler l'écriture/création */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="10" y="20" font-size="12" fill="rgba(255,255,255,0.03)">✍️</text><text x="45" y="55" font-size="14" fill="rgba(255,255,255,0.03)">📝</text><text x="75" y="85" font-size="10" fill="rgba(255,255,255,0.03)">✨</text></svg>');
            background-repeat: repeat;
            opacity: 0.3;
            pointer-events: none;
        }

        .login-container {
            width: 100%;
            max-width: 1100px;
            display: flex;
            background: white;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
            z-index: 1;
        }

        /* Section gauche - Illustration et branding */
        .hero-section {
            flex: 1;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '✍️';
            position: absolute;
            font-size: 280px;
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

        .illustration {
            position: relative;
            z-index: 1;
            margin: 40px 0;
        }

        .illustration-content {
            color: white;
        }

        .feature-list {
            list-style: none;
            margin-top: 24px;
        }

        .feature-list li {
            color: #cbd5e1;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .feature-list li span:first-child {
            font-size: 20px;
        }

        .testimonial {
            position: relative;
            z-index: 1;
            margin-top: auto;
            padding-top: 32px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .testimonial p {
            color: #e2e8f0;
            font-style: italic;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .testimonial-author {
            color: #94a3b8;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Section droite - Formulaire */
        .form-section {
            flex: 1;
            padding: 48px 48px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .form-header p {
            color: #64748b;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        input {
            width: 100%;
            padding: 14px;
            font-size: 15px;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            transition: all 0.2s ease;
            background: #ffffff;
            font-family: inherit;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
            color: #475569;
            font-weight: normal;
            margin: 0;
        }

        .checkbox-label input {
            width: auto;
            padding: 0;
            margin: 0;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #5a67d8;
            text-decoration: underline;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: #764ba2;
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            margin-bottom: 24px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .register-link p {
            color: #64748b;
            font-size: 14px;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin-left: 4px;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Alertes */
        .alert {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .alert strong {
            color: #991b1b;
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
            color: #b91c1c;
            font-size: 13px;
        }

        .alert li {
            margin: 3px 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 500px;
            }

            .hero-section {
                padding: 32px 28px;
                text-align: center;
            }

            .feature-list {
                text-align: left;
            }

            .form-section {
                padding: 36px 28px;
            }

            .form-header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Section gauche - Branding et inspiration création d'articles -->
        <div class="hero-section">
            <div class="brand">
                <h2>Studio d'écriture</h2>
                <p>Plateforme de création d'articles et de contenus éditoriaux</p>
            </div>

            <div class="illustration">
                <div class="illustration-content">
                </div>
            </div>
        </div>

        <!-- Section droite - Formulaire de connexion -->
        <div class="form-section">
            <div class="form-header">
                <h1>Connexion</h1>
                <p>Accédez à votre espace de création d'articles</p>
            </div>

            <?php if (isset($errors) && count($errors) > 0): ?>
                <div class="alert">
                    <strong>Erreur de connexion</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= $baseUrl ?>/login">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['_csrf'], ENT_QUOTES) ?>">

                <div class="form-group">
                    <label>Email professionnel</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? 'admin@irannews.com', ENT_QUOTES) ?>" placeholder="votre@email.com"  required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <div class="input-wrapper">
                        <input type="password" value="AdminPass123" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember"> Se souvenir de moi
                    </label>
                    <a href="#" class="forgot-link">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn-primary">
                    Se connecter →
                </button>

                <div class="register-link">
                    <p>
                        Vous n'avez pas encore de compte ?
                        <a href="<?= $baseUrl ?>/register">Créer un compte</a>
                    </p>
                </div>
            </form>

            <!-- Informations rôles discrètes -->
            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
                <div style="display: flex; gap: 16px; justify-content: center; font-size: 12px; color: #94a3b8;">
                    <span>Admin : accès total</span>
                    <span>Rédacteur : création d'articles</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
