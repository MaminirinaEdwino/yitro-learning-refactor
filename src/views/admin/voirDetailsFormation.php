<?php
$formation_id = $params["id"];
$formation_details = $params["formation_details"];
$sous_formations = $params["sous_formations"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de <?= htmlspecialchars($formation_details->getNom_formation()) ?></title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/journalLog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Ajoutez des styles pour les boutons d'action */
        .btn-action.btn-edit {
            background-color: #ffc107;
        }

        .btn-action.btn-delete {
            background-color: #dc3545;
        }

        .btn-action {
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="sidebar">...</div>

    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Détail</span>
                <h2>Sous-Formations de : <?= htmlspecialchars($formation_details->getNom_formation()) ?></h2>
            </div>
        </div>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <a href="/gestion/formation" class="btn-action btn-view" style="margin-bottom: 20px; display: inline-block; background-color: #6c757d;">
            <i class="fas fa-arrow-left"></i> Retour aux Formations
        </a>

        <div class="gest--container">
            <h2 class="gest--title">Liste des Sous-Formations Existantes</h2>
            <div class="table--wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Contenu</th>
                            <th>Nom de la Sous-Formation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="gest-list-contenu">
                        <?php if (!empty($sous_formations)): ?>
                            <?php foreach ($sous_formations as $contenu): ?>
                                <tr class="table--row">
                                    <td><?= $contenu['id_contenu'] ?></td>
                                    <td><?= htmlspecialchars($contenu['sous_formation']) ?></td>
                                    <td>
                                        <a href="modifier_contenu.php?id=<?= $contenu['id_contenu'] ?>" class="btn-action btn-edit">Modifier</a>
                                        <a href="supprimer_contenu.php?id=<?= $contenu['id_contenu'] ?>" class="btn-action btn-delete" onclick="return confirm('Supprimer cette sous-formation ?')">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align: center;">Aucune sous-formation n'existe pour cette formation principale.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>

</html>