<?php
$cours_statuts = $params["cours_status"];
$cours= $params["cours"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Cours - Yitro Learning</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/mesCours.css">
    <link rel="icon" href="<?= URL_ROOT ?>asset/images/Yitro_consulting.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php require_once './src/components/headerapprenant.php' ?>

    <section class="courses-section">
        <h2 class="section-title">Mes Cours</h2>
        <div class="courses-grid">
            <?php if (empty($cours)): ?>
                <div class="no-courses">
                    <p>Aucun cours disponible. Inscrivez-vous à un cours dès maintenant !</p>
                    <a href="espace_apprenant.php" class="btn-learn">Voir les cours</a>
                </div>
            <?php else: ?>
                <?php foreach ($cours as $c): ?>
                    <div class="course-card">
                        <div class="course-img">
                            <?php if ($c['photo']): ?>
                                <img src="../../Uploads/cours/<?php echo htmlspecialchars($c['photo']); ?>" alt="<?php echo htmlspecialchars($c['titre']); ?>">
                            <?php else: ?>
                                <img src="<?= URL_ROOT ?>asset/images/default_course.jpg" alt="Image par défaut">
                            <?php endif; ?>
                        </div>
                        <div class="course-content">
                            <h3><?php echo htmlspecialchars($c['titre']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($c['description'], 0, 100)) . (strlen($c['description']) > 100 ? '...' : ''); ?></p>
                            <div class="price"><?php echo number_format($c['prix'], 2); ?> €</div>
                            <div class="course-status <?php echo $cours_statuts[$c['id']]['is_completed'] ? 'completed' : 'in-progress'; ?>">
                                Statut : <?php echo $cours_statuts[$c['id']]['is_completed'] ? 'Terminé' : 'En cours (' . round($cours_statuts[$c['id']]['progress']) . '%)'; ?>
                            </div>
                            <a href="cours_details.php?id=<?php echo $c['id']; ?>" class="btn-learn">
                                <?php echo $cours_statuts[$c['id']]['is_completed'] ? 'Voir les détails' : 'Continuer'; ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once './src/components/footer.php' ?>
</body>

</html>