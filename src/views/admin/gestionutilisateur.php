<?php

$search = $params["search"];
$sort = $params["search"];
$order = $params["search"];
// Requête pour les apprenants
$users = $params["users"];
$formtrs = $params["formateurs"];
$admins = $params["admins"];

        
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yitro Learning - Admin</title>
  <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
  <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/journalLog.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <style>
    .error-message {
        color: red;
        text-align: center;
        margin-bottom: 15px;
    }
    .success-message {
        color: green;
        text-align: center;
        margin-bottom: 15px;
    }
    .btn-send-code {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }
    .btn-send-code:hover {
        background-color: #218838;
    }
  </style>
</head>
<body>
  <?php require_once './src/components/sidebaradmin.php'?>
  <div class="main--content">
      <!--  <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Gestion des Utilisateurs</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Rechercher...">
                </div>
                <img src="../asset/images/lito.jpg" alt="User Profile">
            </div>
            </div>
        -->
        <!-- Messages d'erreur ou de succès -->
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>    
        <?php elseif (isset($success_message)): ?>
            <p class="success-message"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <!-- Filtres et export -->
        <div class="filter--container">
            <form method="GET" class="search--form">
                <input type="text" name="search" placeholder="Rechercher par nom ou email" value="<?= htmlspecialchars($search) ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            <div class="sort--export">
                <select onchange="window.location.href='?sort='+this.value+'&order=<?= $order ?>'">
                    <option value="id" <?= $sort == 'id' ? 'selected' : '' ?>>Trier par ID</option>
                    <option value="nom" <?= $sort == 'nom' ? 'selected' : '' ?>>Trier par Nom/Prénom</option>
                    <option value="email" <?= $sort == 'email' ? 'selected' : '' ?>>Trier par Email</option>
                </select>
                <a href="/export/csv" class="btn-action btn-export"><i class="fas fa-download"></i> Exporter CSV</a>
            </div>
        </div>

        <!-- Gestion Apprenants -->
        <div class="gest--container">
            <h2 class="gest--title">Gestion des Apprenants</h2>
            <div class="table--wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="gest-list-apprenants">
                        <?php foreach ($users as $user): ?>
                            <tr class="table--row">
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['nom']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= $user['actif'] ? 'Actif' : 'Inactif' ?></td>
                                <td>
                                    <a href="/voir/user/<?= $user['id'] ?>" class="btn-action btn-view">Voir</a>
                                    <a href="/suivi/apprenant/<?= $user['id'] ?>" class="btn-action btn-track">Suivi</a>
                                    <form method="POST" style="display:inline;" action="/user/activate/<?= $user['id'] ?>">
                                        <input type="hidden" name="action" value="toggle_active">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn-action btn-toggle"><?= $user['actif'] ? 'Désactiver' : 'Activer' ?></button>
                                    </form>
                                    <a href="/user/delete/<?= $user['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Supprimer cet apprenant ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Gestion Formateurs -->
        <div class="gest--container">
            <h2 class="gest--title">Gestion des Formateurs</h2>
            <div class="table--wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="gest-list-formateurs">
                        <?php foreach ($formtrs as $formtr): ?>
                            <tr class="table--row">
                                <td><?= $formtr['id'] ?></td>
                                <td><?= htmlspecialchars($formtr['nom_prenom']) ?></td>
                                <td><?= htmlspecialchars($formtr['email']) ?></td>
                                <td><?= htmlspecialchars($formtr['statut']) ?></td>
                                <td>
                                    <a href="voir_formateurs.php?id=<?= $formtr['id'] ?>" class="btn-action btn-view">Voir</a>
                                    <a href="controle_qualite.php?id=<?= $formtr['id'] ?>" class="btn-action btn-quality">Contrôle Qualité</a>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="update_statut">
                                        <input type="hidden" name="id" value="<?= $formtr['id'] ?>">
                                        <select name="statut" onchange="this.form.submit()">
                                            <option value="en_attente" <?= $formtr['statut'] == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                            <option value="verifie" <?= $formtr['statut'] == 'verifie' ? 'selected' : '' ?>>Vérifié</option>
                                            <option value="premium" <?= $formtr['statut'] == 'premium' ? 'selected' : '' ?>>Premium</option>
                                            <option value="partenaire" <?= $formtr['statut'] == 'partenaire' ? 'selected' : '' ?>>Partenaire</option>
                                        </select>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="send_code">
                                        <input type="hidden" name="id" value="<?= $formtr['id'] ?>">
                                        <button type="submit" class="btn-action btn-send-code">Envoyer le code</button>
                                    </form>
                                    <a href="supprimer_formateur.php?id=<?= $formtr['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Supprimer ce formateur ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Gestion Administrateurs -->
        <div class="gest--container">
            <h2 class="gest--title">Gestion des Administrateurs</h2>
            <div class="table--wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="gest-list-admins">
                        <?php foreach ($admins as $admin): ?>
                            <tr class="table--row">
                                <td><?= $admin['id'] ?></td>
                                <td><?= htmlspecialchars($admin['nom']) ?></td>
                                <td><?= htmlspecialchars($admin['email']) ?></td>
                                <td><?= htmlspecialchars($admin['role']) ?></td>
                                <td><?= $admin['actif'] ? 'Actif' : 'Inactif' ?></td>
                                <td>
                                    <a href="voir_utilisateurs.php?id=<?= $admin['id'] ?>" class="btn-action btn-view">Voir</a>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="toggle_active">
                                        <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                                        <button type="submit" class="btn-action btn-toggle"><?= $admin['actif'] ? 'Désactiver' : 'Activer' ?></button>
                                    </form>
                                    <a href="supprimer_utilisateur.php?id=<?= $admin['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Supprimer cet administrateur ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Lien vers le journal d'activité -->
        <div class="gest--container">
            <h2 class="gest--title">Journal d'Activité</h2>
            <a href="journal_activite.php" class="btn-action btn-view">Voir le Journal d'Activité</a>
        </div>
    </div>
    <script>
        // Animations GSAP pour les conteneurs
        gsap.from(".gest--container", {
            opacity: 0,
            y: 50,
            duration: 1,
            stagger: 0.2,
            ease: "power3.out"
        });

        // Animations pour les lignes de tableau
        gsap.from(".table--row", {
            opacity: 0,
            x: -20,
            duration: 0.8,
            stagger: 0.05,
            ease: "power2.out",
            delay: 0.5
        });

        // Animation pour le filtre
        gsap.from(".filter--container", {
            opacity: 0,
            y: 20,
            duration: 0.8,
            ease: "power2.out"
        });

        // Confirmation avant envoi du code
        document.querySelectorAll('.btn-send-code').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Voulez-vous envoyer un code d\'inscription à ce formateur ?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>