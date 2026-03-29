# Documentation Technique - Mini-projet Iran

## 1. Informations generales

- Projet: Mini-projet Iran (FrontOffice + BackOffice + PostgreSQL)
- Stack: PHP natif, Apache (Docker), PostgreSQL
- FrontOffice: http://localhost:8080
- BackOffice: http://localhost:8081

## 2. Equipe (Num ETU)

- ETU003241
- ETU003337

## 3. Captures d'ecran FrontOffice (1 capture par feature)

### Feature FO-01 - Liste des articles

Capture:
![FO liste articles](captures/fo_01_liste_articles.png)

Explication:
- Affichage de la liste avec article principal (featured), cartes d'articles et pagination.
- Les liens utilisent des URLs propres de type /articles/{slug}.

### Feature FO-02 - Detail d'un article

Capture:
![FO detail article](captures/fo_02_detail_article.png)

Explication:
- Affichage du contenu HTML de l'article, image de couverture et galerie associee.
- Metadonnees article visibles (categorie, date, auteur si present).

### Feature FO-03 - Recherche et filtre par categorie

Capture:
![FO recherche et filtre](captures/fo_03_recherche_filtre.png)

Explication:
- Barre de recherche plein texte + filtres categories.
- Requete combinee: mot-cle + categorie + pagination.

### Feature FO-04 - Navigation categories en mobile

Capture:
![FO navigation mobile categories](captures/fo_04_navigation_mobile_categories.png)

Explication:
- Navigation categories adaptee mobile en grille pour eviter les categories coupees.
- Zones cliquables optimisees pour tactile.

### Feature FO-05 - SEO technique (robots/sitemap)

Capture:
![FO robots et sitemap](captures/fo_05_seo_endpoints.png)

Explication:
- Endpoint robots.txt dynamique.
- Endpoint sitemap.xml dynamique construit a partir des articles publies.

### Feature FO-06 - Performance images (Lighthouse / Network)

Capture:
![FO performance images](captures/fo_06_performance_images.png)

Explication:
- Activation cache HTTP + compression GZip/Deflate.
- Redimensionnement dynamique des images (logo, hero, miniatures, galerie) + WebP + srcset/sizes.

## 4. Captures d'ecran BackOffice (1 capture par feature)

### Feature BO-01 - Ecran d'accueil BackOffice

Capture:
![BO dashboard home](captures/bo_01_dashboard_home.png)

Explication:
- Ecran home technique affichant l'etat de connexion base de donnees.
- Route actuelle: /home/index.

### Feature BO-02 - Login BackOffice (informations de compte)

Capture:
![BO login](captures/bo_02_login_placeholder.png)

Explication:
- Les comptes par defaut sont precharges dans la base (voir section 6).
- Note: dans l'etat actuel du code fourni, la route active observee est /home/index (ecran login non branche dans ce build).

## 5. Modelisation de la base de donnees

### 5.1 Tables principales

- type_utilisateur
- utilisateur
- article_statu
- article_categorie
- article
- article_images

### 5.2 Relations (ERD simplifie)

```mermaid
erDiagram
    TYPE_UTILISATEUR ||--o{ UTILISATEUR : "id_type_utilisateur"
    UTILISATEUR ||--o{ ARTICLE : "id_utilisateur"
    ARTICLE_CATEGORIE ||--o{ ARTICLE : "id_article_categorie"
    ARTICLE_STATU ||--o{ ARTICLE : "id_article_statu"
    ARTICLE ||--o{ ARTICLE_IMAGES : "id_article"

    TYPE_UTILISATEUR {
        int id_type_utilisateur PK
        string libelle
    }

    UTILISATEUR {
        int id_utilisateur PK
        string nom
        string email
        string mot_de_passe
        date date_creation
        int id_type_utilisateur FK
    }

    ARTICLE_CATEGORIE {
        int id_article_categorie PK
        string libelle
    }

    ARTICLE_STATU {
        int id_article_statu PK
        string libelle
    }

    ARTICLE {
        int id_article PK
        string titre
        text contenu
        string slug
        string image_principale
        date date_creation
        datetime date_publication
        string meta_title
        string meta_description
        int id_article_categorie FK
        int id_article_statu FK
        int id_utilisateur FK
    }

    ARTICLE_IMAGES {
        int id_article_images PK
        string url
        int position_
        string legend
        bool est_actif
        int id_article FK
    }
```

## 6. BackOffice - compte par defaut (user/pass)

Source: seed SQL du projet.

- Admin BackOffice
- Email: admin@irannews.com
- Mot de passe: AdminPass123

- Redacteur
- Email: redacteur@irannews.com
- Mot de passe: RedacPass123

URL BackOffice:
- http://localhost:8081
- Route active observee: http://localhost:8081/home/index

## 7. Checklist de finalisation document

- Remplacer toutes les captures placeholders par vos vraies captures.
- Verifier que chaque feature FO/BO a bien une capture + explication courte.
- Exporter ce fichier en .doc si necessaire pour la livraison finale.
