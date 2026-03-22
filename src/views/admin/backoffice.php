<?php
$new_apprenants = $params["new_apprenant"];
$new_formateurs = $params["new_formateurs"];
$new_cours = $params["new_cours"];
$inactive_users = $params["inactive_users"];
$formateurs = $params["formateurs"];
$apprenants_count = $params["apprenant_count"];
$formateurs_count = $params["formateurs_count"];
$cours_count = $params["cours_count"];
$activites_aujourdhui = $params["activite_log"];
$dernieres_activites = $params["last_log"];
$has_notifications = ($new_apprenants > 0 || $new_formateurs > 0 || $new_cours > 0) && !isset($_SESSION['notifications_read']);
$inscriptions = $params["inscriptions"];
$labels_inscriptions = array_keys($inscriptions);
$data_inscriptions = array_values($inscriptions);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Tableau de Bord Admin</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/backOffice.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>

<body>
    <?php require_once './src/components/sidebaradmin.php' ?>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Administration</span>
                <h2>Tableau de Bord</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher...">
                </div>
                <img src="<?= URL_ROOT ?>asset/images/lito.jpg" alt="User Profile">
            </div>
        </div>




        <!-- Notifications -->
        <?php if ($has_notifications): ?>
            <div class="notifications--container">
                <h3>Notifications Récentes
                    <form action="/notification/read" method="POST" style="display:inline;">
                        <button type="submit" name="mark_notification_read" class="btn-mark-read">Marquer comme lues</button>
                    </form>
                </h3>
                <?php if ($new_apprenants > 0): ?>
                    <div class="notification">
                        <i class="fas fa-users"></i>
                        <p><?= $new_apprenants ?> nouvel<?= $new_apprenants > 1 ? 's' : '' ?> apprenant<?= $new_apprenants > 1 ? 's' : '' ?> ajouté<?= $new_apprenants > 1 ? 's' : '' ?> dans les dernières 24 heures.</p>
                    </div>
                <?php endif; ?>
                <?php if ($new_formateurs > 0): ?>
                    <div class="notification">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p><?= $new_formateurs ?> nouvel<?= $new_formateurs > 1 ? 's' : '' ?> formateur<?= $new_formateurs > 1 ? 's' : '' ?> ajouté<?= $new_formateurs > 1 ? 's' : '' ?> dans les dernières 24 heures.</p>
                    </div>
                <?php endif; ?>
                <?php if ($new_cours > 0): ?>
                    <div class="notification">
                        <i class="fas fa-book-open"></i>
                        <p><?= $new_cours ?> nouveau<?= $new_cours > 1 ? 'x' : '' ?> cours ajouté<?= $new_cours > 1 ? 's' : '' ?> dans les dernières 24 heures.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Statistiques -->
        <div class="stats--container">
            <div class="stat--card">
                <div class="stat--icon"><i class="fas fa-users"></i></div>
                <div class="stat--info">
                    <h3><?= $apprenants_count ?></h3>
                    <p>Apprenants</p>
                </div>
            </div>
            <div class="stat--card">
                <div class="stat--icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat--info">
                    <h3><?= $formateurs_count ?></h3>
                    <p>Formateurs</p>
                </div>
            </div>
            <div class="stat--card">
                <div class="stat--icon"><i class="fas fa-book-open"></i></div>
                <div class="stat--info">
                    <h3><?= $cours_count ?></h3>
                    <p>Cours</p>
                </div>
            </div>
            <div class="stat--card">
                <div class="stat--icon"><i class="fas fa-tasks"></i></div>
                <div class="stat--info">
                    <h3><?= $activites_aujourdhui ?></h3>
                    <p>Activités aujourd'hui</p>
                </div>
            </div>
        </div>

        <!-- Utilisateurs inactifs -->
        <div class="inactive-users--container">
            <h3>Utilisateurs Inactifs (30 derniers jours)</h3>
            <div class="table--wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Dernière Activité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($inactive_users)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">Aucun utilisateur inactif.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($inactive_users as $user): ?>
                                <tr class="table--row">
                                    <td><?= htmlspecialchars($user['nom']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($user['role'] === 'apprenant' && in_array($user['id'], array_column($formateurs, 'id')) ? 'formateur' : $user['role'])) ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($user['last_activity']))) ?></td>
                                    <td>
                                        <a href="#" class="btn-action btn-remind">Envoyer un rappel</a>
                                        <form action="/user/deactivate" method="POST" style="display:inline;">
                                            <input type="hidden" name="deactivate_user_id" value="<?= $user['id'] ?>">
                                            <?php
                                            if ($user['actif']) {
                                            ?>
                                                <button type="submit" class="btn-action btn-deactivate" onclick="return confirm('Voulez-vous vraiment désactiver ce compte ?')">Désactiver</button>
                                            <?php
                                            }
                                            ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Graphique des inscriptions -->
        <div class="chart--container">
            <h3>Inscriptions par Mois</h3>
            <canvas id="inscriptionsChart"></canvas>
        </div>

        <!-- Dernières activités -->
        <div class="activites--container">
            <h3>Dernières Activités</h3>
            <div class="table--wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Administrateur</th>
                            <th>Action</th>
                            <th>Détails</th>
                            <th>Voir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dernieres_activites)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">Aucune activité récente.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dernieres_activites as $activite): ?>
                                <tr class="table--row">
                                    <td><?= htmlspecialchars($activite['created_at']) ?></td>
                                    <td><?= htmlspecialchars($activite['nom']) ?></td>
                                    <td><?= htmlspecialchars($activite['action']) ?></td>
                                    <td><?= htmlspecialchars($activite['details']) ?></td>
                                    <td>
                                        <a href="/journal" class="btn-action btn-view">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            // Initialisation du graphique Chart.js
            const ctx = document.getElementById('inscriptionsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($labels_inscriptions) ?>,
                    datasets: [{
                        label: 'Nouvelles Inscriptions',
                        data: <?= json_encode($data_inscriptions) ?>,
                        borderColor: '#01ae8f',
                        backgroundColor: 'rgba(1, 174, 143, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Animations GSAP
            gsap.from(".header--wrapper", {
                opacity: 0,
                y: -20,
                duration: 0.8,
                ease: "power3.out"
            });
            gsap.from(".notifications--container", {
                opacity: 0,
                y: 30,
                duration: 0.8,
                ease: "power3.out",
                delay: 0.1
            });
            gsap.from(".notification", {
                opacity: 0,
                x: -20,
                duration: 0.8,
                stagger: 0.1,
                ease: "power2.out",
                delay: 0.2
            });
            gsap.from(".stat--card", {
                opacity: 0,
                y: 30,
                duration: 0.8,
                stagger: 0.1,
                ease: "power3.out",
                delay: 0.3
            });
            gsap.from(".inactive-users--container", {
                opacity: 0,
                y: 30,
                duration: 0.8,
                ease: "power3.out",
                delay: 0.4
            });
            gsap.from(".chart--container", {
                opacity: 0,
                y: 30,
                duration: 0.8,
                ease: "power3.out",
                delay: 0.5
            });
            gsap.from(".activites--container", {
                opacity: 0,
                y: 30,
                duration: 0.8,
                ease: "power3.out",
                delay: 0.6
            });
            gsap.from(".table--row", {
                opacity: 0,
                x: -20,
                duration: 0.8,
                stagger: 0.05,
                ease: "power2.out",
                delay: 0.7
            });
            gsap.from(".alert", {
                opacity: 0,
                y: 20,
                duration: 0.8,
                ease: "power3.out",
                delay: 0.0
            });
        </script>
    </div>
</body>

</html>