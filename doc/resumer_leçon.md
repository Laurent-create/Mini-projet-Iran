Voici un résumé des techniques SEO et de réécriture d'URL extraites de vos supports de cours, ainsi que la checklist finale pour votre mini-projet.

### I. Techniques de Référencement Naturel (SEO)

[cite_start]Le SEO repose sur trois piliers fondamentaux : la technique, le contenu et la popularité[cite: 3].

#### 1\. SEO On-Page (Contenu et Balisage)

  * [cite_start]**Intention de recherche** : Il est crucial d'identifier si l'utilisateur cherche une information, un site précis, ou souhaite réaliser un achat[cite: 5].
  * **Balises HTML essentielles** :
      * [cite_start]`<title>` : Doit être unique, faire entre 50 et 60 caractères et placer le mot-clé principal au début[cite: 7].
      * [cite_start]`<h1>` : Un seul par page, contenant impérativement le mot-clé principal[cite: 7].
      * [cite_start]`<meta description>` : Entre 150 et 160 caractères pour inciter au clic sans être tronquée[cite: 7].
      * [cite_start]`<h2>` à `<h6>` : Utilisés pour structurer sémantiquement le contenu avec des mots-clés secondaires[cite: 7].
      * [cite_start]`<img> alt` : Doit contenir une description précise de l'image pour l'accessibilité et le référencement[cite: 9].
  * [cite_start]**Optimisation du contenu** : Intégrer les mots-clés dans l'URL, le titre, le premier paragraphe et les balises Hn, tout en évitant le "keyword stuffing" (répétition abusive) qui peut mener à des pénalités[cite: 6, 7].

#### 2\. SEO Technique (Performance et Indexation)

  * [cite_start]**Core Web Vitals** : Le temps de chargement du plus grand élément visible (LCP) doit être inférieur à 2,5 secondes[cite: 11].
  * **Mobile-First** : Depuis 2023, Google indexe les sites en priorité via leur version mobile. [cite_start]L'utilisation de la balise `<meta name='viewport'>` et d'un design responsive est obligatoire[cite: 11].
  * **Outils d'indexation** :
      * [cite_start]`robots.txt` : Pour contrôler l'accès des robots aux différentes pages[cite: 11].
      * [cite_start]`sitemap.xml` : Pour lister les URLs importantes à indexer[cite: 11].
  * [cite_start]**Données structurées** : Utiliser le format JSON-LD (Schema.org) pour enrichir les résultats de recherche avec des "Rich Snippets"[cite: 12, 13].

#### 3\. SEO Off-Page (Popularité)

  * [cite_start]La qualité d'un lien (backlink) dépend de l'autorité du domaine source et de sa pertinence thématique par rapport à votre site[cite: 13].

-----

### II. Techniques de Rewriting (Réécriture d'URL)

La réécriture d'URL permet de transformer des adresses complexes en liens "propres" et lisibles.

#### 1\. Objectifs de la réécriture

  * [cite_start]**Améliorer le référencement** : Les moteurs de recherche préfèrent les URLs contenant des mots-clés plutôt que des paramètres obscurs[cite: 178].
  * [cite_start]**Simplifier la navigation** : Une URL comme `/articles/article-12.html` est plus claire pour l'utilisateur que `/article.php?id=12`[cite: 178].
  * [cite_start]**Sécurité** : Elle permet de masquer la technologie utilisée en back-end (ex: cacher l'extension `.php`)[cite: 178].

#### 2\. Mise en œuvre technique

  * [cite_start]**Serveur Apache** : Utilisation du fichier `.htaccess` avec l'activation du module `mod_rewrite` via la commande `RewriteEngine On`[cite: 178].
  * **Règles de réécriture (`RewriteRule`)** :
      * [cite_start]On définit un modèle (souvent avec des expressions régulières) et on le redirige vers l'URL réelle[cite: 178].
      * [cite_start]Le drapeau `[L]` (Last) est souvent utilisé pour indiquer qu'il s'agit de la dernière règle à appliquer si la condition est remplie[cite: 178].

-----

### III. Checklist de validation du Mini-Projet

[cite_start]Une fois votre site et votre backoffice réalisés, vérifiez les points suivants avant la livraison[cite: 220]:

1.  [cite_start]**URLs normalisées** : Vérifier que l'URL rewriting est fonctionnel et que les liens sont lisibles[cite: 220].
2.  [cite_start]**Structure sémantique** : S'assurer de la hiérarchie logique des balises de titre (`h1` à `h6`)[cite: 220].
3.  [cite_start]**Titres de pages** : Vérifier que chaque page possède un titre unique et pertinent[cite: 220].
4.  [cite_start]**Balises Meta** : Contrôler la présence et la longueur des descriptions meta[cite: 220].
5.  [cite_start]**Attributs Alt** : Vérifier que toutes les images possèdent un texte alternatif descriptif[cite: 220].
6.  [cite_start]**Tests de performance** : Effectuer un test **Lighthouse** en local, aussi bien sur mobile que sur ordinateur[cite: 220].
7.  **Livrables techniques** :
      * [cite_start]Site fonctionnel contenu dans des conteneurs **Docker**[cite: 220].
      * [cite_start]Dépôt en ligne public (GitHub ou GitLab)[cite: 220].
      * [cite_start]Document technique incluant les captures d'écran du FrontOffice/BackOffice et la modélisation de la base de données[cite: 220].

-----

**[Télécharger le résumé au format .md](https://www.google.com/search?q=%23)**
*(Note : En tant qu'IA, je vous fournis ici le contenu formaté en Markdown que vous pouvez copier-coller dans un fichier nommé `resume_seo.md`)*