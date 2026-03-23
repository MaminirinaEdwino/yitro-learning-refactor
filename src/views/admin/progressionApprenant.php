<?php
$apprenants = $params["apprenants"];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Progression des Apprenants</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/progressionApprenantAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
    </style>
</head>
<body>
    <?php require_once './src/components/sidebaradmin.php'?>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Administration</span>
                <h2>Progression des Apprenants</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher...">
                </div>
                <img src="../asset/images/lito.jpg" alt="User Profile">
            </div>
        </div>

        <div class="table--wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Cours</th>
                        <th>Progression Cours</th>
                        <th>Progression Quiz</th>
                        <th>Date d'inscription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($apprenants)): ?>
                        <tr>
                            <td colspan="6" class="no-data">Aucun apprenant trouvé.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($apprenants as $apprenant): ?>
                            <?php if (empty($apprenant['cours'])): ?>
                                <tr class="table--row">
                                    <td><?php echo htmlspecialchars($apprenant['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($apprenant['email']); ?></td>
                                    <td>Aucun cours</td>
                                    <td>
                                        <div class="progress-bar progress-bar-cours">
                                            <div class="progress" style="width: 0%;"></div>
                                            <span>0%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress-bar progress-bar-quiz">
                                            <div class="progress" style="width: 0%;"></div>
                                            <span>0%</span>
                                        </div>
                                    </td>
                                    <td>-</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($apprenant['cours'] as $index => $cours): ?>
                                    <tr class="table--row">
                                        <?php if ($index === 0): ?>
                                            <td rowspan="<?php echo count($apprenant['cours']); ?>">
                                                <?php echo htmlspecialchars($apprenant['nom']); ?>
                                            </td>
                                            <td rowspan="<?php echo count($apprenant['cours']); ?>">
                                                <?php echo htmlspecialchars($apprenant['email']); ?>
                                            </td>
                                        <?php endif; ?>
                                        <td><?php echo htmlspecialchars($cours['cours_titre']); ?></td>
                                        <td>
                                            <div class="progress-bar progress-bar-cours">
                                                <div class="progress" style="width: <?php echo $cours['progression_cours']; ?>%;"></div>
                                                <span><?php echo $cours['progression_cours']; ?>%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="progress-bar progress-bar-quiz">
                                                <div class="progress" style="width: <?php echo $cours['progression_quiz']; ?>%;"></div>
                                                <span><?php echo $cours['progression_quiz']; ?>%</span>
                                            </div>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($cours['date_inscription'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Animations GSAP
        gsap.from(".header--wrapper", { 
            opacity: 0, 
            y: -20, 
            duration: 0.8, 
            ease: "power3.out" 
        });
        gsap.from(".table--wrapper", { 
            opacity: 0, 
            y: 30, 
            duration: 0.8, 
            ease: "power3.out",
            delay: 0.2 
        });
        gsap.from(".table--row", { 
            opacity: 0, 
            x: -20, 
            duration: 0.8, 
            stagger: 0.05, 
            ease: "power2.out",
            delay: 0.4 
        });
    </script>
</body>
</html>