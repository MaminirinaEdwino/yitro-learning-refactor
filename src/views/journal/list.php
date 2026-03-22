<?php
$activites = $params["activites"];
$sort = $params["sort"];
$search = $params["search"];
$order = $params["order"];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning | Journal d'Activité</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/journalLog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Administration</span>
                <h2>Journal d'Activité</h2>
            </div>
            <a href="/admin/backoffice" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
        </div>
        
        <!-- Filtres et recherche -->
        <div class="filter--container">
            <form method="GET" class="search--form">
                <input type="text" name="search" placeholder="Rechercher par action, détails, administrateur ou date (ex: 2025-05-27)" value="<?= htmlspecialchars($search) ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            <div class="sort--export">
                <select onchange="window.location.href='?sort='+this.value+'&order=<?= $order ?>'">
                    <option value="created_at" <?= $sort == 'created_at' ? 'selected' : '' ?>>Trier par Date</option>
                    <option value="action" <?= $sort == 'action' ? 'selected' : '' ?>>Trier par Action</option>
                    <option value="nom" <?= $sort == 'nom' ? 'selected' : '' ?>>Trier par Administrateur</option>
                </select>
                <a href="?order=<?= $order == 'ASC' ? 'desc' : 'asc' ?>&sort=<?= $sort ?>" class="btn-action btn-toggle">
                    <i class="fas fa-sort-<?= $order == 'ASC' ? 'up' : 'down' ?>"></i> <?= $order == 'ASC' ? 'Ascendant' : 'Descendant' ?>
                </a>
            </div>
        </div>

        <div class="card--container">
            <div class="card--wrapper">
                <h3>Activités Récentes</h3>
                <div class="table--wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Administrateur</th>
                                <th>Action</th>
                                <th>Détails</th>
                                <th>Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activites as $act): ?>
                                <tr class="table--row">
                                    <td><?= htmlspecialchars($act['created_at']) ?></td>
                                    <td><?= htmlspecialchars($act['nom']) ?></td>
                                    <td><?= htmlspecialchars($act['action']) ?></td>
                                    <td><?= htmlspecialchars($act['details']) ?></td>
                                    <td>
                                        <form method="POST" style="display:inline;" action="/journal/delete/<?= $act['id'] ?>">
                                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Supprimer cette entrée du journal ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Animations GSAP pour les conteneurs
        gsap.from(".card--wrapper", { 
            opacity: 0, 
            y: 50, 
            duration: 1, 
            ease: "power3.out" 
        });
        // Animations pour les lignes de tableau
        gsap.from(".table--row", { 
            opacity: 0, 
            x: -20, 
            duration: 0.8, 
            stagger: 0.05, 
            ease: "power2.out" 
        });
        // Animation pour le filtre
        gsap.from(".filter--container", {
            opacity: 0,
            y: 20,
            duration: 0.8,
            ease: "power2.out"
        });
    </script>
</body>
</html>