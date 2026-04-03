<?php
// --- Variables de Message ---

require_once "./src/repositories/contenueFormation.repositories.php";
require_once "./src/repositories/cours.repositories.php";
require_once "./src/repositories/utilisateurs.repositories.php";

$sousFormationRepo = new ContenueFormationRepositories();
$coursRepo = new CoursRepositories();
$userRepo = new UtilisateursRepositories();

$success_message = '';
$error_message = '';

//la liste des Formations principales (pour les listes déroulantes et l'affichage)
$formations = $params["formations"];
$sous_formations = $params["sous_formations"];
$inscriptions = $params["inscriptions"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Gestion des formations</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/journalLog.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/gestionFormation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>

<body>
    <?php require_once './src/components/sidebaradmin.php' ?>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Gestion</span>
                <h2>Gestion des Formations</h2>
            </div>
        </div>

        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php elseif (!empty($success_message)): ?>
            <p class="success-message"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <div class="forms--container">

            <div class="form-section gest--container">
                <h3>Ajouter une Formation Principale</h3>
                <form method="POST" action="/formation/new">
                    <label for="nom_formation">Nom de la Formation :</label>
                    <input type="text" id="nom_formation" name="nom_formation" required>

                    <button type="submit" name="ajouter_formation">Ajouter la Formation</button>
                </form>
            </div>

            <div class="form-section gest--container">
                <h3>Ajouter une Sous-Formation</h3>
                <form method="POST" action="/sousformation/new">
                    <label for="formation_parent_id">Sélectionner la Formation :</label>

                    <select id="formation_parent_id" name="formation_parent_id" required>
                        <option value="">-- Choisir une formation --</option>
                        <?php
                        // Affichage des formations chargées en amont
                        if (!empty($formations)) {
                            foreach ($formations as $formation) {
                                echo '<option value="' . htmlspecialchars($formation->getId_formation()) . '">'
                                    . htmlspecialchars($formation->getNom_formation()) .
                                    '</option>';
                            }
                        } else {
                            echo '<option value="" disabled>Aucune formation trouvée.</option>';
                        }
                        ?>
                    </select>

                    <label for="sous_formation">Nom de la Sous-Formation :</label>
                    <input type="text" id="sous_formation" name="sous_formation" required>

                    <button type="submit" name="ajouter_sous_formation">Ajouter la Sous-Formation</button>
                </form>
            </div>
        </div>

        <div class="gest--container">
            <h2 class="gest--title">Liste des Inscriptions aux cours</h2>
            <div class="table--wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cours</th>
                            <th>Apprenant</th>
                            <th>Date payement</th>
                            <th>Méthode</th>
                            <th>Réferences</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="gest-list-formations">
                        <?php
                        if (!empty($inscriptions)):
                            foreach ($inscriptions as $inscription):
                                $coursInscription = $coursRepo->GetById($inscription->getCoursId());
                                $apprenantInscription = $userRepo->GetById($inscription->getUtilisateurId());
                                $date = $inscription->getDateInscription();
                        ?>
                                <tr class="table--row">
                                    <td><?= $coursInscription->getTitre() ?></td>
                                    <td><?= $apprenantInscription->getNom() ?></td>
                                    <td><?= date("d/m/Y H:i", $date->getTimestamp()) ?></td>
                                    <td><?= $inscription->getMethodPayement() ?></td>
                                    <td><?= $inscription->getReferencePayement() ?></td>
                                    <td><?= $inscription->getStatutPayement() ?></td>
                                    <td>
                                        <?php if ($inscription->getStatutPayement() == "en_attente"): ?>
                                            <a href="/valid/inscription/<?= $inscription->getId() ?>" class="btn-action btn-edit">Valider</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">Aucune Inscription</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="gest--container">
            <h2 class="gest--title">Liste des Formations Principales</h2>
            <div class="table--wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Formation</th>
                            <th>Nom de la Formation</th>
                            <th>Nombre de Sous-Formations</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="gest-list-formations">
                        <?php
                        if (!empty($formations)):
                            foreach ($formations as $formation):
                                // Compter le nombre de sous-formations pour l'affichage

                                $count = $sousFormationRepo->CountSousFormation($formation->getId_formation());
                        ?>
                                <tr class="table--row">
                                    <td><?= $formation->getId_formation() ?></td>
                                    <td><?= htmlspecialchars($formation->getNom_formation()) ?></td>
                                    <td><?= $count ?></td>
                                    <td>
                                        <a href="/formation/edit/<?= $formation->getId_formation() ?>" class="btn-action btn-edit">Modifier</a>

                                        <a href="/gestion/formation/<?= $formation->getId_formation() ?>" class="btn-action btn-view">Voir Toutes</a>

                                        <a href="/formation/delete/<?= $formation->getId_formation() ?>" class="btn-action btn-delete" onclick="return confirm('Supprimer la formation <?= htmlspecialchars($formation->getNom_formation()) ?> et TOUTES ses sous-formations ?')">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">Aucune formation principale n'a été ajoutée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        // Animations GSAP pour les conteneurs
        gsap.from(".forms--container, .gest--container", {
            opacity: 0,
            y: 50,
            duration: 1,
            stagger: 0.2,
            ease: "power3.out"
        });
    </script>
</body>

</html>