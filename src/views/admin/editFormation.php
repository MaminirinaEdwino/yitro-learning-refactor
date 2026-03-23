<?php
$formation_details = $params["formations"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Formation Principale</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/journalLog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

        .form-container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        input[type="text"],
        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .info-box {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php require_once './src/components/sidebaradmin.php' ?>

    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Modification</span>
                <h2>Modifier la Formation Principale</h2>
            </div>
        </div>

        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php elseif (!empty($success_message)): ?>
            <p class="success-message"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <div class="form-container">
            <p>Vous modifiez la formation ID : <strong><?= htmlspecialchars($formation_details->getId_formation() ?? 'N/A') ?></strong></p>

            <form method="POST" action="/formation/edit/<?= $formation_details->getId_formation() ?>">
                <input type="hidden" name="id_formation" value="<?= htmlspecialchars($formation_details->getId_formation() ?? '') ?>">

                <label for="nouveau_nom">Nouveau nom de la Formation :</label>
                <input type="text" id="nouveau_nom" name="nouveau_nom"
                    value="<?= htmlspecialchars($formation_details->getNom_formation() ?? '') ?>" required>

                <button type="submit" name="update_formation">Enregistrer la Modification</button>
            </form>

            <a href="/gestion/formation" class="btn-action btn-view" style="display: block; text-align: center; background-color: #6c757d;">
                Annuler et Retour à la Gestion
            </a>
        </div>
    </div>
</body>

</html>