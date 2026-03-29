
# Scénario 1 : Infrastructure et Setup Initial

## Tâche 1

**Catégorie** : Infrastructure
**Module** : Setup
**Tâches** : Création du repository Git (GitHub/GitLab public)
**Type** : Configuration
**Qui** : ETU003241
**Estimation** : 10

---

## Tâche 2

**Catégorie** : Infrastructure
**Module** : Setup
**Tâches** : Mise en place Docker (FrontOffice + BackOffice + DB)
**Type** : Configuration
**Qui** : ETU003337
**Estimation** : 15

---

## Tâche 3

**Catégorie** : Infrastructure
**Module** : Setup
**Tâches** : Initialisation projet BackOffice
**Type** : Configuration
**Qui** : ETU003241
**Estimation** : 10

---

## Tâche 4

**Catégorie** : Infrastructure
**Module** : Setup
**Tâches** : Initialisation projet FrontOffice
**Type** : Configuration
**Qui** : ETU003337
**Estimation** : 10

---

# Scénario 2 : Base de Données

## Tâche 5

**Catégorie** : Infrastructure
**Module** : Base de données
**Tâches** : Conception du MCD
**Type** : Conception
**Qui** : ETU003241
**Estimation** : 30

---

# Scénario 3 : Backend - BackOffice

## Tâche 8

**Catégorie** : Backend
**Module** : Authentification
**Tâches** : Implémentation login/logout admin
**Type** : Développement
**Qui** : ETU003241
**Estimation** : 30

---

## Tâche 9

**Catégorie** : Backend
**Module** : Articles
**Tâches** : CRUD articles (create, edit, delete, publish)
**Type** : Développement
**Qui** : ETU003241
**Estimation** : 180

---

## Tâche 10

**Catégorie** : Backend
**Module** : Catégories
**Tâches** : CRUD catégories
**Type** : Développement
**Qui** : ETU003241
**Estimation** : 30

---
 
## Tâche 12

**Catégorie** : Backend
**Module** : Upload
**Tâches** : API upload image (TinyMCE + stockage partagé)
**Type** : Développement
**Qui** : ETU003241
**Estimation** : 60

## Tâche 12

**Catégorie** : Backend
**Module** : Upload
**Tâches** : API upload image (TinyMCE + stockage partagé)
**Type** : Développement
**Qui** : ETU003241
**Estimation** : 60

---

## Tâche 13

**Catégorie** : Backend
**Module** : SEO
**Tâches** : Gestion slug automatique + meta title + meta description
**Type** : Développement
**Qui** : ETU003241
**Estimation** : 120

---

# Scénario 4 : FrontOffice

## Tâche 14

**Catégorie** : Frontend Web
**Module** : Setup
**Tâches** : Configuration du routing FrontOffice en PHP natif (index.php, URLs propres /articles et /articles/{slug})
**Type** : Développement
**Qui** : ETU003337
**Estimation** : 60

---

## Tâche 15

**Catégorie** : Frontend Web
**Module** : Articles
**Tâches** : Développement de la page liste des articles (featured, pagination, catégories, métadonnées d'affichage)
**Type** : Développement
**Qui** : ETU003337
**Estimation** : 120

---

## Tâche 16

**Catégorie** : Frontend Web
**Module** : Articles
**Tâches** : Développement de la page détail article (affichage HTML, image de couverture, galerie, auteur, date)
**Type** : Développement
**Qui** : ETU003337
**Estimation** : 150

---

## Tâche 17

**Catégorie** : Frontend Web
**Module** : Navigation
**Tâches** : Implémentation menu catégories + navigation responsive mobile (grille catégories sur petit écran)
**Type** : Développement
**Qui** : ETU003337
**Estimation** : 90

---

## Tâche 18

**Catégorie** : Frontend Web
**Module** : Recherche
**Tâches** : Implémentation recherche articles
**Type** : Développement
**Qui** : ETU003337
**Estimation** : 120

---

## Tâche 19

**Catégorie** : Frontend Web
**Module** : Gestion d'erreurs
**Tâches** : Intégration de la page 404 FrontOffice et fallback contrôleur pour article introuvable
**Type** : Développement
**Qui** : ETU003337
**Estimation** : 45

---

# Scénario 5 : Design & UX

## Tâche 20

**Catégorie** : Frontend Web
**Module** : Design
**Tâches** : Design responsive mobile + desktop (layout, typographie, sections featured/cards)
**Type** : Design
**Qui** : ETU003337
**Estimation** : 180

---

## Tâche 21

**Catégorie** : Frontend Web
**Module** : UI
**Tâches** : Intégration UI (cards articles, header/footer, navigation catégories, composants pagination)
**Type** : Développement
**Qui** : ETU003337
**Estimation** : 150

---

## Tâche 22

**Catégorie** : Frontend Web
**Module** : UX
**Tâches** : Amélioration de lisibilité mobile (navigation catégories, zones cliquables, hiérarchie visuelle)
**Type** : Développement
**Qui** : ETU003337
**Estimation** : 90

---

# Scénario 6 : SEO & Optimisation

## Tâche 23

**Catégorie** : SEO
**Module** : On-Page
**Tâches** : Intégration SEO On-Page (title, meta description, canonical, H1/H2, alt images)
**Type** : Développement
**Qui** : ETU003337
**Estimation** : 120

---

## Tâche 24

**Catégorie** : SEO
**Module** : Technique
**Tâches** : Mise en place SEO technique (sitemap.xml dynamique, robots.txt dynamique, JSON-LD)
**Type** : Développement
**Qui** : ETU003241
**Estimation** : 90

---

## Tâche 25

**Catégorie** : SEO
**Module** : Performance
**Tâches** : Optimisation Lighthouse (cache HTTP, compression GZip/Deflate, lazy loading, fetchpriority)
**Type** : Développement
**Qui** : ETU003337
**Estimation** : 150

---

## Tâche 26

**Catégorie** : SEO
**Module** : Images
**Tâches** : Implémentation du redimensionnement dynamique des images (logo, hero, miniatures, galerie) avec génération WebP et srcset/sizes
**Type** : Développement
**Qui** : ETU003337
**Estimation** : 150

---

# Scénario 7 : Tests & Livraison

## Tâche 27

**Catégorie** : Tests
**Module** : Fonctionnel
**Tâches** : Tests complets FO + BO
**Type** : Test
**Qui** : ETU003241
**Estimation** : 60

---

## Tâche 28

**Catégorie** : Documentation
**Module** : Technique
**Tâches** : Rédaction document technique (MCD + captures FO/BO)
**Type** : Documentation
**Qui** : ETU003337
**Estimation** : 180

---

## Tâche 29

**Catégorie** : Livraison
**Module** : Final
**Tâches** : Préparation ZIP + dépôt Git final
**Type** : Livraison
**Qui** : ETU003241
**Estimation** : 30

