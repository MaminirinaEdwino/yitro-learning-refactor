CREATE TABLE `completions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `module_id` int NOT NULL,
  `cours_id` int NOT NULL,
  `date_completion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `utilisateur_id` (`utilisateur_id`,`module_id`),
  KEY `module_id` (`module_id`),
  KEY `cours_id` (`cours_id`),
  CONSTRAINT `completions_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `completions_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`),
  CONSTRAINT `completions_ibfk_3` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `contact` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sujet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `contenu_formations` (
  `id_contenu` int NOT NULL AUTO_INCREMENT,
  `formation_id` int NOT NULL,
  `sous_formation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_contenu`),
  KEY `fk_contenu_formation_id` (`formation_id`),
  CONSTRAINT `fk_contenu_formation_id` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id_formation`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cours` (
  `id` int NOT NULL AUTO_INCREMENT,
  `formateur_id` int NOT NULL,
  `formation_id` int NOT NULL,
  `contenu_formation_id` int NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `niveau` enum('Débutant','Intermédiaire','Avancé') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `formateur_id` (`formateur_id`),
  KEY `formation_id` (`formation_id`),
  KEY `contenu_formation_id` (`contenu_formation_id`),
  CONSTRAINT `cours_ibfk_1` FOREIGN KEY (`formateur_id`) REFERENCES `formateurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cours_formation` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id_formation`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cours_sous_formation` FOREIGN KEY (`contenu_formation_id`) REFERENCES `contenu_formations` (`id_contenu`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `formateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_prenom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville_pays` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `intitule_metier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience_formation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `detail_experience` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cv` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categories` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `autre_domaine` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre_cours` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `objectif` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `public_cible` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `detail_complementaire` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `formats` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `format_autre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duree_estimee` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_formation` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motivation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `valeurs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `profil_public` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `statut` enum('en_attente','verifie','premium','partenaire') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `code_entree` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `formations` (
  `id_formation` int NOT NULL AUTO_INCREMENT,
  `nom_formation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_formation`),
  UNIQUE KEY `nom_formation` (`nom_formation`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `forum` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cours_id` int NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cours_id` (`cours_id`),
  CONSTRAINT `forum_ibfk_1` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `forum_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cours_id` int DEFAULT NULL,
  `utilisateur_id` int DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `date` datetime DEFAULT NULL,
  `lu` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cours_id` (`cours_id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `forum_messages_ibfk_1` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`),
  CONSTRAINT `forum_messages_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `inscriptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `cours_id` int NOT NULL,
  `date_inscription` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `statut_paiement` enum('en_attente','paye','echec') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  PRIMARY KEY (`id`),
  UNIQUE KEY `utilisateur_id` (`utilisateur_id`,`cours_id`),
  KEY `cours_id` (`cours_id`),
  CONSTRAINT `inscriptions_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `inscriptions_ibfk_2` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `journal_activite` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int NOT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `journal_activite_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lecons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `module_id` int NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `format` enum('pdf','audio','video') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fichier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`),
  CONSTRAINT `lecons_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `lecons_completees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `lecon_id` int DEFAULT NULL,
  `date_completion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `lecon_id` (`lecon_id`),
  CONSTRAINT `lecons_completees_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `lecons_completees_ibfk_2` FOREIGN KEY (`lecon_id`) REFERENCES `lecons` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `modules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cours_id` int NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cours_id` (`cours_id`),
  CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `post` (
  `id` int NOT NULL AUTO_INCREMENT,
  `auteur_id` int NOT NULL,
  `forum_id` int NOT NULL,
  `contenu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_post` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `auteur_id` (`auteur_id`),
  KEY `forum_id` (`forum_id`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`auteur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `post_ibfk_2` FOREIGN KEY (`forum_id`) REFERENCES `forum` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `texte` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reponse_correcte` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reponse_incorrecte_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reponse_incorrecte_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reponse_incorrecte_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `quiz` (
  `id` int NOT NULL AUTO_INCREMENT,
  `module_id` int NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `score_minimum` int NOT NULL DEFAULT '70',
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`),
  CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `resultats_quiz` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `score` int NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `resultats_quiz_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `resultats_quiz_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `langue` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `autre_langue` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `objectifs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type_cours` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `niveau_formation` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `niveau_etude` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acces_internet` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `appareil` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accessibilite` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rgpd` tinyint(1) DEFAULT '0',
  `charte` tinyint(1) DEFAULT '0',
  `role` enum('apprenant','admin','moderator') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'apprenant',
  `actif` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;