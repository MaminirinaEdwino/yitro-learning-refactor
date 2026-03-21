<?php

$formateur_id = $_SESSION['formateur_id'];

// Récupérer le nom du formateur
$trainer_name = $_SESSION['formateur_nom_prenom'];

// Récupérer les cours du formateur avec les noms de Thème et Sous-Thème
$cours = $params["cours"];

// Récupérer les modules, leçons et forums pour chaque cours
$modules = $params["modules"];
$lecons = $params["lecons"];
$forums = $params["forums"];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Mes cours</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/liste_cours.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/listCoursFormateur.css">
</head>
<body>
    <?php require_once "./src/components/formateurSideBar.php"?>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Mes cours</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher...">
                </div>
                <i class="fas fa-user-circle"></i>
                <span class="trainer-name"><?php echo $trainer_name; ?></span>
            </div>
        </div>

        <div class="course-section">
            <h3>Mes cours</h3>
            <?php if (empty($cours)): ?>
                <div class="no-courses">
                    Aucun cours créé pour le moment. <a href="create_cours.php">Créer un cours</a>.
                </div>
            <?php else: ?>
                <?php foreach ($cours as $c): ?>
                    <div class="course-card">
                        <div class="course-header">
                            <img src="<?php echo '/Uploads/cours/' . $c['photo'] ?>" alt="Photo du cours" class="course-image">
                            <div class="course-info">
                                <div class="course-title-row">
                                    <h4><?php echo htmlspecialchars($c['titre']); ?> <span class="forum-badge"><?php echo count($forums[$c['id']]); ?> Forum(s)</span></h4>
                                
                                    <div class="theme-subtheme-info">
                                        <span class="theme-text"><i class="fas fa-layer-group"></i><?php echo htmlspecialchars($c['nom_theme']); ?></span>
                                        <span class="subtheme-text"><i class="fas fa-tag"></i><?php echo htmlspecialchars($c['nom_sous_theme']); ?></span>
                                    </div>
                                </div>
                                                            
                                <p><?php echo htmlspecialchars(substr($c['description'], 0, 100)) . (strlen($c['description']) > 100 ? '...' : ''); ?></p>
                                <p class="price"><?php echo number_format($c['prix'], 2); ?> €</p>
                                <p class="niveau"><?php echo htmlspecialchars($c['niveau']);?></p>
                                <div class="course-actions">
                                    <a href="/cours/edit/<?php echo $c['id']; ?>" class="btn btn-success"><i class="fas fa-edit"></i> Modifier</a>
                                    <a href="/cours/delete/<?php echo $c['id']; ?>" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce cours ?');"><i class="fas fa-trash"></i> Supprimer</a>
                                    <a href="/forum/cours/<?php echo $c['id']; ?>" class="btn btn-info"><i class="fas fa-comments"></i> Accéder au forum</a>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($modules[$c['id']])): ?>
                            <div class="module-list">
                                <?php foreach ($modules[$c['id']] as $module): ?>
                                    <div class="module-item">
                                        <h5><?php echo htmlspecialchars($module->getTitre()); ?></h5>
                                        <p><?php echo htmlspecialchars(substr($module->getDescription(), 0, 80)) . (strlen($module->getDescription()) > 80 ? '...' : ''); ?></p>
                                        <div class="course-actions">
                                            <a href="/module/edit/<?php echo $module->getId(); ?>" class="btn btn-success btn-sm"><i class="fas fa-edit"></i> Modifier </a>
                                            <a href="/module/delete/<?php echo $module->getId(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce module ?');"><i class="fas fa-trash"></i> Supprimer</a>
                                        </div>
                                        
                                        <?php if (!empty($lecons[$module->getId()])): ?>
                                            <div class="lesson-list">
                                                <?php foreach ($lecons[$module->getId()] as $lecon): ?>
                                                    <div class="lesson-item">
                                                        <div class="lesson-info">
                                                            <i class="fas <?php
                                                                echo $lecon->getFormat() === 'pdf' ? 'fa-file-pdf' :
                                                                    ($lecon->getFormat()  === 'audio' ? 'fa-file-audio' : 'fa-file-video');
                                                            ?>"></i>
                                                            <span><?php echo htmlspecialchars($lecon->getTitre()) . ' (' . $lecon->getFormat() . ')'; ?></span>
                                                            <?php if ($lecon->getFichier() && file_exists('./Uploads/lecons/' . $lecon->getFichier())): ?>
                                                                <a href="/Uploads/lecons/<?php echo htmlspecialchars($lecon->getFichier()); ?>" class="file-link" target="_blank"><?php echo htmlspecialchars($lecon->getFichier()); ?></a>
                                                            <?php else: ?>
                                                                <span class="file-missing">Fichier non trouvé </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="course-actions">
                                                            <?php if ($lecon->getFichier() && file_exists(URL_ROOT.'Uploads/lecons/' . $lecon->getFichier())): ?>
                                                                <a href="<?= URL_ROOT ?>Uploads/lecons/<?php echo htmlspecialchars($lecon->getFichier()); ?>" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-eye"></i> Voir</a>
                                                            <?php endif; ?>
                                                            <a href="/lecon/edit/<?php echo $lecon->getId(); ?>" class="btn btn-success btn-sm"><i class="fas fa-edit"></i> Modifier</a>
                                                            <a href="/lecon/delete/<?php echo $lecon->getId(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cette leçon ?');"><i class="fas fa-trash"></i> Supprimer</a>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Animations GSAP
        gsap.from(".header--wrapper", { opacity: 0, y: -60, duration: 1.2, ease: "elastic.out(1, 0.5)" });
        gsap.from(".course-card", { opacity: 0, y: 40, rotationX: 10, duration: 1, stagger: 0.2, ease: "power3.out", delay: 0.3 });
        gsap.from(".module-item", { opacity: 0, y: 30, duration: 0.8, stagger: 0.1, ease: "power2.out", delay: 0.5 });
        gsap.from(".lesson-item", { opacity: 0, x: 30, rotationX: 5, duration: 0.6, stagger: 0.1, ease: "power2.out", delay: 0.7 });
        gsap.from(".btn", { opacity: 0, scale: 0.7, duration: 0.6, stagger: 0.1, ease: "back.out(2)", delay: 0.9 });
        gsap.from(".no-courses", { opacity: 0, y: 30, duration: 1, ease: "power3.out", delay: 0.3 });
        // Animation bouton survol
        document.querySelectorAll(".btn").forEach(btn => {
            btn.addEventListener("mouseenter", () => {
                gsap.to(btn, { scale: 1.05, boxShadow: "0 6px 12px rgba(0,0,0,0.2)", duration: 0.3 });
            });
            btn.addEventListener("mouseleave", () => {
                gsap.to(btn, { scale: 1, boxShadow: "0 4px 10px rgba(0,0,0,0.15)", duration: 0.3 });
            });
        });
    </script>
</body>
</html>