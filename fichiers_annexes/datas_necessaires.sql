-- Données créées pour des jeux de tests, pas définitives ! Il faudrait que l'on discute de leur pertinence pour la démo, et les modifier, enrichir si besoin ! 
--Pour que certaines fonctions JS fonctionne correctement, il est impératif de concerver les ID mentionnés (si jamais vous avez déja des données différentes dans les tables ci-dessous, il faudra les supprimer pour les remplacer par celles-ci)

INSERT INTO `annonce_type` (`id`, `title`) VALUES
(1, 'Don'),
(2, 'Service');

-- Attention à bien insérer les category avant les subcategory si non vous aurez des erreurs
INSERT INTO `category` (`id`, `title`) VALUES
(1, 'Maison'),
(2, 'Mode'),
(3, 'Loisirs'),
(4, 'Multimédia'),
(5, 'Autres'),
(6, 'Service');

INSERT INTO `sub_category` (`id`, `title`, `category_id`) VALUES
(1, 'CD - DVD - Bluray', 4),
(2, 'Téléphone - Tablette', 4),
(3, 'Ordinateur', 4),
(4, 'Vêtements', 2),
(5, 'Chaussures', 2),
(6, 'Accessoires', 2),
(7, 'Sports', 3),
(8, 'Activités manuelles', 3),
(9, 'Mobilier', 1),
(10, 'Décoration', 1),
(11, 'Indéfinie', 5),
(17, 'Cours', 6),
(18, 'Garde', 6),
(19, 'Bricolage', 6),
(20, 'Entretien', 6),
(21, 'Compagnie', 6);

INSERT INTO `status` (`id`, `title`) VALUES
(1, 'À saisir'),
(2, 'Réservé'),
(3, 'Effectué');

INSERT INTO `condition` (`id`, `title`) VALUES
(1, 'Neuf'),
(2, 'Très bon '),
(3, 'Bon'),
(4, 'Moyen'),
(5, 'À bricoler');