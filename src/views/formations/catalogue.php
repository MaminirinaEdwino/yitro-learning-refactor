<?php





// // Récupérer toutes les formations pour le menu déroulant (Thèmes)
// $formations = [];
// try {
//     $stmt_formations = $pdo->prepare("SELECT id_formation, nom_formation FROM formations ORDER BY nom_formation");
//     $stmt_formations->execute();
//     $formations = $stmt_formations->fetchAll(PDO::FETCH_ASSOC);
// } catch (PDOException $e) {
//     error_log("Erreur de requête des formations : " . $e->getMessage());
// }

// $cours = [];
// try {
//     $sql_cours = "
//         SELECT 
//             c.id, 
//             c.titre, 
//             c.description, 
//             c.prix, 
//             c.niveau,
//             c.photo,
//             c.formation_id,         
//             c.contenu_formation_id,
//             f.nom_formation AS nom_theme,
//             cf.sous_formation AS nom_sous_theme
//         FROM 
//             cours c
//         LEFT JOIN   
//             formations f ON c.formation_id = f.id_formation
//         LEFT JOIN  
//             contenu_formations cf ON c.contenu_formation_id = cf.id_contenu
//         ORDER BY 
//             c.titre ASC"; 

//     $stmt = $pdo->prepare($sql_cours);
//     $stmt->execute();
//     $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
// } catch (PDOException $e) {
//     error_log("Erreur de requête des cours : " . $e->getMessage());
//     die("Erreur Fatale de Base de Données: " . $e->getMessage() . "<br>Veuillez vérifier votre requête SQL."); 
// }?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue - Yitro Learning</title>
    <link rel="icon" href="<?= URL_ROOT ?>asset/images/Yitro_consulting.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/catalogue.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/catalogue2.css">
</head>
<body>
    <!-- Header -->
    <?php require_once './src/components/header.php'?>

    <!-- Hero Section -->
    <section class="catalogue-hero">
        <div class="catalogue-hero-content">
            <h1>Explorez nos formations</h1>
            <p>Découvrez une large gamme de cours conçus par des experts pour booster vos compétences. Trouvez la formation parfaite pour vous dès aujourd'hui !</p>
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Rechercher une formation..." aria-label="Rechercher une formation">
                <button type="button" onclick="filterCourses()"><i class="fas fa-search"></i></button>
            </div>
        </div>
        <canvas id="hero-animation"></canvas>
    </section>

    <!-- Main Catalogue Section -->
    <div class="catalogue-main">
        <aside class="catalogue-sidebar">
            <h2>Filtres</h2>
            
            <div class="filter-group">
                <label for="theme_filter">Thème</label>
                <select id="theme_filter" onchange="loadAndFilterCourses()">
                    <option value="">Tous les Thèmes</option>
                    <?php foreach ($formations as $f): ?>
                        <option value="<?php echo $f['id_formation']; ?>"><?php echo htmlspecialchars($f['nom_formation']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group" id="subtheme-group">
                <label for="subtheme_filter">Sous-Thème</label>
                <select id="subtheme_filter" onchange="filterCourses(true)">
                    <option value="">Tous les Sous-Thèmes</option>
                    </select>
            </div>
            
            <div class="filter-group">
                <label for="level_filter">Niveau</label>
                <select id="level_filter" onchange="filterCourses(true)">
                    <option value="">Tous les Niveaux</option>
                    <option value="Débutant">Débutant</option>
                    <option value="Intermédiaire">Intermédiaire</option>
                    <option value="Avancé">Avancé</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="price_filter">Prix</label>
                <select id="price_filter" onchange="filterCourses(true)">
                    <option value="">Tous</option>
                    <option value="free">Gratuit</option>
                    <option value="paid">Payant</option>
                </select>
            </div>
        </aside>

        <div class="catalogue-content">
            <div class="catalogue-controls">
                <h2>Nos cours</h2>
                <div class="sort-options">
                    <label for="sort">Trier par :</label>
                    <select id="sort" onchange="sortCourses()">
                        <option value="popularity">Popularité</option>
                        <option value="newest">Plus récent</option>
                        <option value="price">Prix</option>
                    </select>
                </div>
            </div>
            <div class="courses-grid" id="courses-grid">
                <?php if (empty($cours)): ?>
                    <p style="grid-column: 1 / -1; text-align: center; color: #555;">Aucun cours disponible pour le moment.</p>
                <?php else: ?>
                    <?php foreach ($cours as $c): ?>
                        <?php
                            $price_text = $c['prix'] == 0 ? 'Gratuit' : number_format($c['prix'], 2) . ' €';
                            $price_data = $c['prix'] == 0 ? 'free' : 'paid';
                            //bouton "Accéder"
                            $access_link = $is_logged_in ? '../Espace/apprenant/cours_detail1.php?id=' . $c['id'] : 'connect-Apprenant.php';
                            $button_text = $is_logged_in ? 'Accéder' : 'Connecter';

                        ?>
                        <div class="course-card" 
                            data-theme="<?php echo htmlspecialchars($c['formation_id']); ?>" 
                            data-subtheme="<?php echo htmlspecialchars($c['contenu_formation_id'] ?? ''); ?>" 
                            data-level="<?php echo htmlspecialchars($c['niveau']); ?>" 
                            data-price="<?php echo $price_data; ?>" 
                            data-price-value="<?php echo $c['prix']; ?>"
                            data-popularity="100" 
                            data-date="<?php echo date('Y-m-d'); ?>" 
                            >
                            <img src="../Uploads/cours/<?php echo htmlspecialchars($c['photo']); ?>" alt="<?php echo htmlspecialchars($c['titre']); ?>">
                            <div class="course-card-content">
                                <h3><?php echo htmlspecialchars($c['titre']); ?></h3>
                                <p class="course-description"><?php echo htmlspecialchars(substr($c['description'], 0, 100)); ?>...</p>
                                <p class="level"><?php echo htmlspecialchars($c['niveau']); ?></p>
                                <p class="price"><?php echo $price_text; ?></p>
                                <!--<span class="badge">Certificat</span>-->
                                <a href="<?php echo $access_link; ?>" class="btn-primary"><?php echo $button_text; ?></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="container">
            <h2>Explorez par catégorie</h2>
            <div class="categories-grid">
                <div class="category-card">
                    <i class="fas fa-laptop-code"></i>
                    <h3>Technologie</h3>
                </div>
                <div class="category-card">
                    <i class="fas fa-bullhorn"></i>
                    <h3>Marketing</h3>
                </div>
                <div class="category-card">
                    <i class="fas fa-briefcase"></i>
                    <h3>Business</h3>
                </div>
                <div class="category-card">
                    <i class="fas fa-heart"></i>
                    <h3>Santé & Bien-être</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Prêt à apprendre ?</h2>
            <p>Rejoignez des milliers d'apprenants et commencez votre parcours dès maintenant.</p>
            <div class="cta-buttons">
                <a href="../authentification/inscription.php" class="btn-primary">S'inscrire</a>
                <a href="contact.php" class="btn-primary">Nous contacter</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once './src/components/footer.php'?>

    <script src="<?= URL_ROOT ?>asset/js/catalogueAnimation.js">
        

    </script>
</body>
</html>