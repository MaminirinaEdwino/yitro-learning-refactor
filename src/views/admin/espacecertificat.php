<?php
// Récupérer les apprenants actifs
$apprenants = $params["apprenants"];

$message = '';
$download_link = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $params["message"];
    $download_link = $params["link"];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Génération de Certificat</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/generationCertificat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>
    <?php require_once './src/components/sidebaradmin.php'?>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Administration</span>
                <h2>Générer un Certificat</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher...">
                </div>
                <img src="<?= URL_ROOT ?>asset/images/lito.jpg" alt="User Profile">
            </div>
        </div>

        <div class="card--wrapper">
            <h3>Formulaire de Génération de Certificat</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="apprenant_id">Sélectionner un Apprenant</label>
                    <select name="apprenant_id" id="apprenant_id" required onchange="updateCours()">
                        <option value="">-- Choisir un apprenant --</option>
                        <?php foreach ($apprenants as $apprenant): ?>
                            <option value="<?php echo $apprenant['id']; ?>">
                                <?php echo htmlspecialchars($apprenant['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cours_id">Sélectionner un Cours</label>
                    <select name="cours_id" id="cours_id" required>
                        <option value="">-- Choisir un cours --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="titre_certificat">Titre du Certificat</label>
                    <input type="text" name="titre_certificat" id="titre_certificat" value="Certificat de Réussite" required>
                </div>
                <div class="form-group">
                    <label for="date_emission">Date d'Émission</label>
                    <input type="date" name="date_emission" id="date_emission" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit">Générer le Certificat</button>
                </div>
            </form>
            <?php if ($message): ?>
                <div class="message <?php echo strpos($message, 'Erreur') === false ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                    <?php if ($download_link): ?>
                        <a href="<?php echo $download_link; ?>" class="download-link" download>Télécharger le Certificat</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
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
        gsap.from(".card--wrapper", { 
            opacity: 0, 
            y: 30, 
            duration: 0.8, 
            ease: "power3.out",
            delay: 0.2 
        });

        // Mettre à jour la liste des cours en fonction de l'apprenant
        function updateCours() {
            const apprenantId = document.getElementById('apprenant_id').value;
            const coursSelect = document.getElementById('cours_id');
            coursSelect.innerHTML = '<option value="">-- Choisir un cours --</option>';

            if (apprenantId) {
                fetch(`/apprenants/cours/${apprenantId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(cours => {
                            const option = document.createElement('option');
                            option.value = cours.id;
                            option.textContent = cours.titre;
                            coursSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Erreur:', error));
            }
        }
    </script>
</body>
</html>