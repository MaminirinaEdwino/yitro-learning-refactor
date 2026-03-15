<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Yitro Learning</title>
    <link rel="icon" href="../asset/images/Yitro_consulting.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/connexion.css">
</head>
<body>
    <!-- Header -->
    <?php require_once './src/components/header.php'?>

    <!-- Section Hero -->
    <section class="heros">
        <div class="heros-content">
            <h1>Connectez-vous à Yitro Learning</h1>
            <p>Accédez à votre espace formateur ou apprenant pour commencer ou continuer votre parcours d'apprentissage.</p>
            <a href="#connexion" class="cta-buttons">Se connecter maintenant</a>
        </div>
        <canvas id="heros-animation"></canvas>
    </section>

    <!-- Section Connexion -->
    <section class="connexion-section" id="connexion">
        <!-- Formulaire Apprenant -->
        <div class="connexion-card fade-in">
            <div class="connexion-form">
                <h2>Connexion Apprenant</h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<p class="error">' . htmlspecialchars($_SESSION['error']) . '</p>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<p class="success">' . htmlspecialchars($_SESSION['success']) . '</p>';
                    unset($_SESSION['success']);
                }
                ?>
                <form id="apprenantForm" action="connexion-apprenant.php" method="POST">
                    <div class="form-group">
                        <label for="emailApprenant" class="form-label">Adresse e-mail *</label>
                        <input type="email" class="form-control" id="emailApprenant" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordApprenant" class="form-label">Mot de passe *</label>
                        <input type="password" class="form-control" id="passwordApprenant" name="password" minlength="8" required>
                    </div>
                    <button type="submit" class="btn-primary">Se connecter</button>
                </form>
            </div>
            <div class="connexion-guide">
                <h3>Guide pour les Apprenants</h3>
                <p>Connectez-vous pour accéder à vos cours, suivre votre progression et obtenir vos certificats.</p>
                <ul>
                    <li>Utilisez l'e-mail et le mot de passe de votre inscription.</li>
                    <li>Si vous avez oublié votre mot de passe, contactez-nous à <a href="mailto:support@yitro-consulting.com">support@yitro-consulting.com</a>.</li>
                    <li>Pas encore inscrit ? <a href="inscription.php">Créez un compte</a>.</li>
                </ul>
            </div>
        </div>

        <!-- Formulaire Formateur -->
        <div class="connexion-card fade-in">
            <div class="connexion-form">
                <h2>Connexion Formateur</h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<p class="error">' . htmlspecialchars($_SESSION['error']) . '</p>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<p class="success">' . htmlspecialchars($_SESSION['success']) . '</p>';
                    unset($_SESSION['success']);
                }
                ?>
                <form id="formateurForm" action="connexion-formateur.php" method="POST">
                    <div class="form-group">
                        <label for="emailFormateur" class="form-label">Adresse e-mail *</label>
                        <input type="email" class="form-control" id="emailFormateur" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordFormateur" class="form-label">Mot de passe *</label>
                        <input type="password" class="form-control" id="passwordFormateur" name="password" minlength="8" required>
                    </div>
                    <button type="submit" class="btn-primary">Se connecter</button>
                </form>
            </div>
            <div class="connexion-guide">
                <h3>Guide pour les Formateurs</h3>
                <p>Connectez-vous pour gérer vos cours, interagir avec vos apprenants et suivre leurs progrès.</p>
                <ul>
                    <li>Utilisez l'e-mail fourni lors de votre candidature.</li>
                    <li>Problèmes de connexion ? Contactez <a href="mailto:formateur@yitro-consulting.com">formateur@yitro-consulting.com</a>.</li>
                    <li>Envie de rejoindre notre équipe ? <a href="../page/formulaire-formateur.php">Postulez ici</a>.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once './src/components/footer.php'?>

    <!-- JavaScript -->
    <script src="<?= URL_ROOT ?>asset/js/connexion.js"></script>
</body>
</html>