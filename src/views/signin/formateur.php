<?php

// Vérifier si le formulaire est soumis
if (isset( $_SESSION["erreur"])) {
    $error_message = $_SESSION["erreur"];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Inscription Formateur</title>
    <link rel="stylesheet" href="../asset/css/styles/style.css">
    <link rel="icon" href="../asset/images/Yitro_consulting.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/inscription-formateur.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .error-message {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }

        .success-message {
            color: green;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <?php require_once './src/components/header.php' ?>

    <!-- Section hero -->
    <section class="heros">
        <div class="heros-content">
            <h1>Intégrez la communauté Yitro Learning et proposez vos formations en toute autonomie, sur mobile, à votre rythme et en toute liberté.</h1>
            <p>Transmettez vos connaissances et inspirez la progression</p>
            <a href="#registrationForm" class="cta-buttons">Inscrivez-vous maintenant</a>
        </div>
        <canvas id="heros-animation"></canvas>
    </section>

    <div class="container my-5">
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php elseif (isset($success_message)): ?>
            <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>
        <form id="registrationForm" action="inscription-formateur.php" method="POST">
            <div class="form-section">
                <h4>Inscription Formateur</h4>
                <div class="mb-3">
                    <label for="nom_prenom" class="form-label">Nom et Prénom</label>
                    <input type="text" class="form-control" id="nom_prenom" name="nom_prenom" required placeholder="Entrez votre nom et prénom">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Entrez votre email">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Entrez votre mot de passe">
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Confirmez votre mot de passe">
                </div>
                <div class="mb-3">
                    <label for="entryCode" class="form-label">Code d'entrée (fourni par l'admin)</label>
                    <input type="text" class="form-control" id="entryCode" name="entryCode" required placeholder="Entrez le code">
                </div>
                <button type="submit" class="cta-buttons">S'inscrire</button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <?php require_once './src/components/footer.php' ?>

    <script src="<?= URL_ROOT ?>asset/js/inscription-formateur.js"></script>
    <script>
        // JavaScript pour gérer l'animation et la visibilité de la barre de navigation
        const topNav = document.querySelector('header');
        let lastScrollTop = 0;
        let isAnimating = false;
        let scrollTimeout = null;

        function handleScroll() {
            if (isAnimating) return;

            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScroll > 100) {
                if (!topNav.classList.contains('hidden')) {
                    isAnimating = true;
                    topNav.classList.add('hidden');
                    setTimeout(() => {
                        topNav.classList.add('invisible');
                        isAnimating = false;
                    }, 500);
                }
            } else {
                if (topNav.classList.contains('hidden') || topNav.classList.contains('invisible')) {
                    isAnimating = true;
                    topNav.classList.remove('invisible');
                    topNav.offsetHeight;
                    topNav.classList.remove('hidden');
                    setTimeout(() => {
                        isAnimating = false;
                    }, 500);
                }
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        }

        window.addEventListener('scroll', () => {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            scrollTimeout = setTimeout(handleScroll, 100);
        });
    </script>
</body>

</html>