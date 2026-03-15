
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisissez votre parcours - Yitro Learning</title>
    <link rel="icon" href="<?= URL_ROOT ?>asset/images/Yitro consulting.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/inscription.css">
</head>
<body>
    <!-- Header -->
   <?php require_once './src/components/header.php'?>

    <!-- Section Hero -->
    <section class="heros">
        <div class="heros-content">
            <h1>Rejoignez Yitro Learning</h1>
            <p>Que vous souhaitiez enseigner ou apprendre, Yitro Learning vous offre une plateforme pour développer vos compétences et partager votre passion.</p>
        </div>
        <canvas id="heros-animation"></canvas>
    </section>

    <!-- Section Choix -->
    <section class="choice-section">
        <h2>Choisissez votre parcours</h2>
        <div class="choice-grid">
            <!-- Carte Formateur -->
            <div class="choice-card fade-in">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>Devenir Formateur</h3>
                <p>Partagez votre expertise avec des milliers d'apprenants et inspirez la prochaine génération de professionnels.</p>
                <a href="../page/inscription-formateur.php" class="btn-choice">S'inscrire comme formateur</a>
            </div>
            <!-- Carte Apprenant -->
            <div class="choice-card fade-in">
                <i class="fas fa-user-graduate"></i>
                <h3>Devenir Apprenant</h3>
                <p>Apprenez à votre rythme avec nos cours interactifs en technologie, programmation et bien plus encore.</p>
                <a href="../page/inscription-apprenant.php" class="btn-choice">S'inscrire comme apprenant</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once './src/components/footer.php'?>

    <!-- JavaScript -->
    <script src="<?= URL_ROOT ?>asset/js/inscription.js">
        
    </script>
</body>
</html>