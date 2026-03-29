CREATE TABLE type_utilisateur(
   Id_type_utilisateur SERIAL,
   libelle VARCHAR(50),
   PRIMARY KEY(Id_type_utilisateur)
);

CREATE TABLE article_statu(
   Id_article_statu SERIAL,
   libelle VARCHAR(50),
   PRIMARY KEY(Id_article_statu)
);

CREATE TABLE article_categorie(
   Id_article_categorie SERIAL,
   libelle VARCHAR(50),
   PRIMARY KEY(Id_article_categorie)
);

CREATE TABLE utilisateur(
   Id_utilisateur SERIAL,
   nom VARCHAR(50),
   email VARCHAR(100),
   mot_de_passe VARCHAR(50),
   date_creation DATE,
   Id_type_utilisateur INTEGER NOT NULL,
   PRIMARY KEY(Id_utilisateur),
   FOREIGN KEY(Id_type_utilisateur) REFERENCES type_utilisateur(Id_type_utilisateur)
);

CREATE TABLE article(
   Id_article SERIAL,
   titre VARCHAR(250),
   contenu TEXT,
   slug VARCHAR(250),
   image_principale VARCHAR(150),
   date_creation DATE,
   date_publication TIMESTAMP,
   meta_title VARCHAR(150),
   meta_description VARCHAR(250),
   Id_article_categorie INTEGER NOT NULL,
   Id_article_statu INTEGER NOT NULL,
   Id_utilisateur INTEGER NOT NULL,
   PRIMARY KEY(Id_article),
   FOREIGN KEY(Id_article_categorie) REFERENCES article_categorie(Id_article_categorie),
   FOREIGN KEY(Id_article_statu) REFERENCES article_statu(Id_article_statu),
   FOREIGN KEY(Id_utilisateur) REFERENCES utilisateur(Id_utilisateur)
);

CREATE TABLE article_images(
   Id_article_images SERIAL,
   url VARCHAR(250),
   position_ INTEGER,
   legend VARCHAR(100),
   est_actif BOOLEAN,
   Id_article INTEGER NOT NULL,
   PRIMARY KEY(Id_article_images),
   FOREIGN KEY(Id_article) REFERENCES article(Id_article)
);


INSERT INTO type_utilisateur (libelle) VALUES
('Admin'),        -- pour gérer le BackOffice
('Rédacteur');    -- pour publier des articles depuis le FrontOffice

INSERT INTO article_statu (libelle) VALUES
('Brouillon'),
('Publié'),
('Archivé');

INSERT INTO article_categorie (libelle) VALUES
('Politique'),
('Conflits'),
('Économie'),
('Culture'),
('Santé');

INSERT INTO utilisateur (nom, email, mot_de_passe, date_creation, Id_type_utilisateur) VALUES
('Admin Iran', 'admin@irannews.com', 'AdminPass123', CURRENT_DATE, 1),      -- Admin BackOffice
('Redacteur Iran', 'redacteur@irannews.com', 'RedacPass123', CURRENT_DATE, 2); -- Rédacteur FrontOffice

-- Articles de démonstration (conflit en Iran)
-- Convention IDs (sur base fraîche):
--   - Catégorie "Conflits" = 2 (ordre d'insertion)
--   - Statuts: 1=Brouillon, 2=Publié, 3=Archivé
--   - Admin = 1
INSERT INTO article (
   titre,
   contenu,
   slug,
   image_principale,
   date_creation,
   date_publication,
   meta_title,
   meta_description,
   Id_article_categorie,
   Id_article_statu,
   Id_utilisateur
) VALUES
(
   'Iran : point de situation sur l''escalade du conflit',
    '<h2>Contexte et précautions</h2>
<p>Ce texte est un article de démonstration destiné à alimenter la base de données au démarrage du projet. Il a été rédigé pour paraître crédible (ton neutre, structuration, prudence sur les informations) mais ne remplace pas une enquête journalistique, ni une source officielle.</p>

<h2>Ce que l''on entend par « escalade »</h2>
<p>Dans un contexte de guerre ou de tensions armées, le terme « escalade » recouvre plusieurs phénomènes : augmentation de l''intensité des combats, élargissement des zones concernées, recours à des armes plus destructrices, multiplication d''incidents transfrontaliers, ou encore durcissement des positions diplomatiques. Dans le cas iranien, ces dynamiques se superposent souvent à des enjeux internes (stabilité politique, économie, sécurité) et à des interactions régionales.</p>

<h2>Éléments de chronologie (repères)</h2>
<ul>
   <li><strong>Phase de tensions</strong> : hausse des incidents, rhétorique plus agressive, renforcement des dispositifs sécuritaires.</li>
   <li><strong>Phase d''incidents majeurs</strong> : attaques revendiquées ou attribuées, ripostes, cycles d''actions et de contre-actions.</li>
   <li><strong>Phase de stabilisation relative</strong> : diminution du rythme des affrontements, canaux de médiation, trêves partielles ou temporaires.</li>
</ul>

<p>Ces repères sont utiles pour analyser la situation sans conclure trop vite : une baisse ponctuelle des violences ne signifie pas toujours un apaisement durable, tout comme une séquence d''incidents ne débouche pas automatiquement sur une guerre totale.</p>

<h2>Conséquences humanitaires : ce qui revient le plus souvent</h2>
<p>Les conséquences humanitaires d''un conflit se traduisent rarement par un seul indicateur. On observe généralement :</p>
<ul>
   <li><strong>Déplacements de population</strong> : familles qui quittent temporairement une zone jugée trop risquée ; saturation des hébergements et services locaux.</li>
   <li><strong>Pression sur les hôpitaux</strong> : afflux de blessés, tensions sur les stocks de médicaments, difficultés de logistique et d''approvisionnement.</li>
   <li><strong>Ruptures de services essentiels</strong> : eau, électricité, télécommunications, transports, selon les zones et l''intensité des opérations.</li>
   <li><strong>Risques pour les civils</strong> : dommages collatéraux, difficultés de circulation, incertitudes autour des zones de combat.</li>
</ul>

<p>Dans les périodes d''escalade, les besoins augmentent souvent plus vite que les capacités de réponse, notamment lorsque les accès routiers se dégradent ou que des restrictions de déplacement entravent l''acheminement de l''aide.</p>

<h2>Enjeux politiques et sécuritaires</h2>
<p>Sur le plan politique, un conflit agit comme un révélateur : il met à l''épreuve la cohésion interne, la confiance dans les institutions, et la capacité des autorités à protéger la population. Sur le plan sécuritaire, la question clé est la <em>prévisibilité</em> : plus les incidents sont difficiles à anticiper, plus les acteurs renforcent leurs postures, ce qui augmente le risque d''erreurs de calcul.</p>

<p>Un autre point d''analyse concerne l''information : en temps de guerre, la circulation de rumeurs et d''images non vérifiées se renforce. Les observateurs sérieux privilégient alors des informations recoupées (plusieurs sources indépendantes, données satellites, communiqués officiels, organisations internationales, ONG reconnues) et évitent de tirer des conclusions définitives à partir d''un seul signal.</p>

<h2>Impact régional : pourquoi la situation dépasse souvent les frontières</h2>
<p>La région est caractérisée par des alliances, des rivalités et des interdépendances énergétiques. Même lorsque les combats restent localisés, les effets peuvent se diffuser :</p>
<ul>
   <li><strong>Risque d''incidents transfrontaliers</strong> (zones frontalières, espace maritime, couloirs aériens).</li>
   <li><strong>Chocs économiques</strong> (prix de l''énergie, assurance maritime, routes commerciales).</li>
   <li><strong>Pression diplomatique</strong> (médiations, sanctions, résolutions, négociations).</li>
</ul>

<h2>À quoi s''attendre ensuite (scénarios)</h2>
<p>Sans prétendre prédire l''évolution, trois scénarios sont fréquemment étudiés :</p>
<ol>
   <li><strong>Escalade prolongée</strong> : cycles d''actions/ripostes, intensité fluctuante, risques d''élargissement.</li>
   <li><strong>Statu quo instable</strong> : baisse relative des combats, mais incidents récurrents et forte incertitude.</li>
   <li><strong>Désescalade négociée</strong> : mécanismes de dialogue, cessez-le-feu partiel, garanties et vérification.</li>
</ol>

<h2>Conclusion</h2>
<p>Pour le suivi de la situation, les éléments les plus utiles sont souvent les plus sobres : évolution des accès humanitaires, capacité des hôpitaux, continuité des services, et signaux de médiation. Les annonces spectaculaires existent, mais elles gagnent à être recoupées avant d''être intégrées à l''analyse.</p>',
   'iran-point-de-situation-escalade-conflit',
   'articles/iran-conflit-1.jpg',
   CURRENT_DATE,
   NOW(),
   'Iran : point de situation sur l''escalade du conflit',
   'Résumé des évènements récents liés au conflit, pour démonstration BackOffice.',
   2,
   2,
   1
),
(
   'Iran : conséquences économiques et logistiques du conflit',
    '<h2>Pourquoi l''économie est rapidement touchée</h2>
<p>Lorsqu''un conflit s''installe, l''économie est souvent touchée avant même que les infrastructures ne soient durablement endommagées. L''incertitude modifie le comportement des ménages (épargne de précaution, achats de stock), des entreprises (report d''investissements) et des transporteurs (réduction des trajets, hausse des primes de risque). Les effets peuvent être progressifs ou brusques, selon l''intensité des opérations et la stabilité des chaînes logistiques.</p>

<h2>Chaînes d''approvisionnement : les points de fragilité</h2>
<p>Les chaînes d''approvisionnement reposent sur des « maillons » : importations de biens essentiels, transport routier, stockage, distribution, paiements et assurances. Dans un contexte de guerre, plusieurs fragilités reviennent souvent :</p>
<ul>
   <li><strong>Transport</strong> : routes moins sûres, retards, contrôles, restrictions de déplacement, carburant plus rare ou plus cher.</li>
   <li><strong>Assurance</strong> : surcoûts pour le fret, primes de risque, clauses d''exclusion, limitation de couverture.</li>
   <li><strong>Financement</strong> : accès au crédit plus difficile, volatilité des devises, baisse de la confiance.</li>
   <li><strong>Stockage</strong> : gestion des stocks plus coûteuse, pertes liées à des ruptures de froid ou à des coupures d''électricité.</li>
</ul>

<h2>Inflation et pouvoir d''achat : mécanismes</h2>
<p>La hausse des prix peut venir de plusieurs canaux :</p>
<ol>
   <li><strong>Coût de l''import</strong> (fret + assurance + délais).</li>
   <li><strong>Coût du transport intérieur</strong> (carburant, risques, détours).</li>
   <li><strong>Rareté</strong> (ruptures temporaires, spéculation, achats de précaution).</li>
</ol>

<p>Pour les ménages, l''effet est souvent visible sur les biens essentiels (alimentation, carburant, médicaments) et sur les services (transports, énergie). Les autorités peuvent réagir par des contrôles de prix, des subventions ciblées ou des distributions, mais ces mesures ont un coût budgétaire et exigent une logistique robuste.</p>

<h2>Énergie et logistique : un couple très sensible</h2>
<p>La logistique dépend de l''énergie (carburant, électricité) et l''énergie dépend de la logistique (maintenance, pièces, distribution). En période de conflit :</p>
<ul>
   <li>les <strong>coupures</strong> perturbent l''industrie, le froid alimentaire et les télécoms,</li>
   <li>les <strong>pannes</strong> deviennent plus longues faute de pièces ou de techniciens disponibles,</li>
   <li>les <strong>réallocations</strong> (priorités militaires/sécuritaires) réduisent les marges pour le civil.</li>
</ul>

<h2>Entreprises : adaptation et arbitrages</h2>
<p>Les entreprises basculent souvent en « mode continuité » :</p>
<ul>
   <li><strong>réduction</strong> de l''exposition (moins de livraisons risquées),</li>
   <li><strong>diversification</strong> des fournisseurs,</li>
   <li><strong>stocks</strong> plus élevés,</li>
   <li><strong>priorisation</strong> des produits à forte demande,</li>
   <li><strong>plans de sécurité</strong> pour les employés.</li>
</ul>

<p>Cette adaptation améliore la résilience à court terme, mais elle augmente les coûts, ce qui nourrit l''inflation et réduit la compétitivité. Dans certains secteurs, la pénurie de main-d''œuvre (mobilisation, déplacements, contraintes familiales) peut devenir un facteur limitant majeur.</p>

<h2>Ce que l''on peut suivre (indicateurs utiles)</h2>
<p>Pour évaluer l''impact économique, les indicateurs les plus parlants sont souvent :</p>
<ul>
   <li>prix des carburants et disponibilité,</li>
   <li>délais de transport et taux de rupture (produits essentiels),</li>
   <li>activité portuaire et routière (lorsqu''elle est mesurable),</li>
   <li>tension sur la monnaie (écart officiel/parallèle lorsque pertinent),</li>
   <li>signaux de confiance (investissement, emploi, consommation).</li>
</ul>

<h2>Conclusion</h2>
<p>Dans un conflit, l''économie ne s''arrête pas, mais elle se reconfigure autour du risque et de la survie : la logistique devient plus chère, moins prévisible, et plus inégale selon les régions. La crédibilité d''une analyse dépend alors de sa prudence : distinguer les faits observables, les hypothèses, et ce qui reste incertain.</p>',
   'iran-consequences-economiques-logistiques-conflit',
   'articles/iran-conflit-2.jpg',
   CURRENT_DATE,
   NOW(),
   'Iran : conséquences économiques et logistiques du conflit',
   'Impacts économiques et logistiques liés au conflit (article de démo).',
   2,
   2,
   1
);


