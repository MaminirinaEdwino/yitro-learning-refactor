<?php
$formateur_id = $_SESSION['formateur_id'];
$trainer_name = $_SESSION['formateur_nom_prenom'];
$cours = $params["cours"];
$progression = $params["progression"];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Progression des apprenants</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/progressionApprenantFormateur.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>
    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
            <li>
                <a href="espace_formateur.php"><i class="fas fa-tachometer-alt"></i><span>Tableau de bord</span></a>
            </li>
            <li>
                <a href="create_cours.php"><i class="fas fa-user-cog"></i><span>Créer un cours</span></a>
            </li>
            <li>
                <a href="liste_cours.php"><i class="fas fa-folder-open"></i><span>Mes cours</span></a>
            </li>
            <li class="active">
                <a href="progression_apprenants.php"><i class="fas fa-chart-line"></i><span>Progression des apprenants</span></a>
            </li>
            <li>
                <a href="liste_quiz.php"><i class="fas fa-question-circle"></i><span>Gestion des quiz</span></a>
            </li>
            <li class="logout">
                <a href="/logout"><i class="fas fa-sign-out-alt"></i><span>Déconnexion</span></a>
            </li>
        </ul>
    </div>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Progression des apprenants</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher...">
                </div>
                <i class="fas fa-users"></i>
                <span class="trainer-name"><?php echo $trainer_name; ?></span>
            </div>
        </div>
        <h1>Progression des apprenants</h1>

        <?php if (empty($cours)): ?>
            <div class="no-progression">
                Aucun cours disponible. Créez un cours pour suivre la progression des apprenants !
            </div>
        <?php else: ?>
            <?php foreach ($cours as $c): ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlspecialchars($c['titre']); ?></h4>
                        <?php if (empty($progression[$c['id']]['apprenants'])): ?>
                            <p>Aucun apprenant inscrit à ce cours.</p>
                        <?php else: ?>
                            <div class="progression-list">
                                <?php foreach ($progression[$c['id']]['apprenants'] as $utilisateur_id => $data): ?>
                                    <div class="progression-item">
                                        <h5><?php echo htmlspecialchars($data['nom']); ?></h5>
                                        <div class="progress-bar">
                                            <div class="progress-bar-fill" style="width: <?php echo $data['progression']; ?>%;"></div>
                                        </div>
                                        <p>Progression : <?php echo number_format($data['progression'], 1); ?>% (<?php echo count($data['modules_termines']); ?> / <?php echo $progression[$c['id']]['total_modules']; ?> modules terminés)</p>
                                        <div class="toggle-progression" onclick="toggleDetails(this)">Voir les détails</div>
                                        <div class="progression-details">
                                            <div class="completed-modules">
                                                <strong>Modules terminés :</strong>
                                                <?php if (empty($data['modules_termines'])): ?>
                                                    <p>Aucun module terminé.</p>
                                                <?php else: ?>
                                                    <ul>
                                                        <?php foreach ($data['modules_termines'] as $module): ?>
                                                            <li><?php echo htmlspecialchars($module); ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="quiz-results">
                                            <h6>Résultats des quiz</h6>
                                            <?php if (empty($data['resultats_quiz'])): ?>
                                                <p>Aucun quiz complété.</p>
                                            <?php else: ?>
                                                <div class="toggle-quiz" onclick="toggleQuizDetails(this)">Voir les résultats des quiz</div>
                                                <div class="quiz-details">
                                                    <?php foreach ($data['resultats_quiz'] as $index => $resultat): ?>
                                                        <div class="quiz-result-item <?php echo $resultat['score'] >= $resultat['score_minimum'] ? 'valid' : 'invalid'; ?>">
                                                            <span><strong><?php echo htmlspecialchars($resultat['quiz_titre']); ?></strong></span>
                                                            <span>Score : <?php echo $resultat['score']; ?>% (Min : <?php echo $resultat['score_minimum']; ?>%)</span>
                                                            <span>Date : <?php echo date('d/m/Y', strtotime($resultat['date'])); ?></span>
                                                            <canvas id="quiz-chart-<?php echo $utilisateur_id . '-' . $c['id'] . '-' . $index; ?>" class="quiz-chart"></canvas>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        function toggleDetails(element) {
            const details = element.nextElementSibling;
            details.classList.toggle('active');
            element.textContent = details.classList.contains('active') ? 'Masquer les détails' : 'Voir les détails';
        }

        function toggleQuizDetails(element) {
            const details = element.nextElementSibling;
            details.classList.toggle('active');
            element.textContent = details.classList.contains('active') ? 'Masquer les résultats des quiz' : 'Voir les résultats des quiz';
        }

        // Initialiser les graphiques Chart.js pour les résultats des quiz
        <?php foreach ($cours as $c): ?>
            <?php foreach ($progression[$c['id']]['apprenants'] as $utilisateur_id => $data): ?>
                <?php foreach ($data['resultats_quiz'] as $index => $resultat): ?>
                    new Chart(document.getElementById('quiz-chart-<?php echo $utilisateur_id . '-' . $c['id'] . '-' . $index; ?>'), {
                        type: 'bar',
                        data: {
                            labels: ['Score', 'Min'],
                            datasets: [{
                                data: [<?php echo $resultat['score']; ?>, <?php echo $resultat['score_minimum']; ?>],
                                backgroundColor: [
                                    '<?php echo $resultat['score'] >= $resultat['score_minimum'] ? '#2ecc71' : '#dc3545'; ?>',
                                    '#e5e7eb'
                                ],
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    display: false // Masquer l'axe Y pour compacter
                                },
                                x: {
                                    display: false // Masquer l'axe X pour compacter
                                }
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: { enabled: false } // Désactiver les tooltips pour simplifier
                            }
                        }
                    });
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>

        gsap.from(".main--content", { opacity: 0, y: 50, duration: 1, ease: "power3.out" });
        gsap.from(".card, .no-progression", { opacity: 0, y: 30, duration: 0.8, stagger: 0.1, ease: "power2.out", delay: 0.2 });
        gsap.from(".progression-item", { opacity: 0, y: 20, duration: 0.6, stagger: 0.1, ease: "power2.out", delay: 0.3 });
        gsap.from(".progress-bar-fill", { width: 0, duration: 1, ease: "power2.out", delay: 0.5 });
        gsap.from(".quiz-result-item", { opacity: 0, y: 10, duration: 0.5, stagger: 0.05, ease: "power2.out", delay: 0.6 });
    </script>
</body>
</html>