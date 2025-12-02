<?php
session_start();
// Connexion à la base de données
$host = "localhost";
$dbname = "yitro_learning";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Activer les erreurs PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error_message = "Erreur de connexion : " . $e->getMessage();
    error_log("Erreur connexion DB : " . $e->getMessage());
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer et sécuriser les données
    $nom_prenom = htmlspecialchars(trim($_POST["nom_prenom"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $entryCode = trim($_POST["entryCode"]);

    // Nettoyage supplémentaire du code pour supprimer les espaces ou caractères invisibles
    $entryCode = preg_replace('/\s+/', '', $entryCode);

    // Journaliser les données saisies pour débogage
    error_log("Inscription formateur - Email: $email, Code: $entryCode");

    // Vérifications
    if (empty($nom_prenom) || empty($email) || empty($password) || empty($confirmPassword) || empty($entryCode)) {
        $error_message = "Tous les champs sont requis.";
    } elseif ($password !== $confirmPassword) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "L'email n'est pas valide.";
    } else {
        try {
            // Vérifier si le code d'entrée est valide pour cet email
            $checkCode = $pdo->prepare("SELECT id FROM formateurs WHERE LOWER(email) = LOWER(?) AND code_entree = ?");
            $checkCode->execute([$email, $entryCode]);
            if ($checkCode->rowCount() === 0) {
                $error_message = "Code d'entrée invalide ou email non autorisé.";
                error_log("Échec validation - Email: $email, Code: $entryCode, Résultat: aucun formateur trouvé");
            } else {
                // Vérifier si l'email est déjà utilisé par un formateur avec un mot de passe (inscription complète)
                $checkEmail = $pdo->prepare("SELECT id FROM formateurs WHERE email = ? AND password IS NOT NULL");
                $checkEmail->execute([$email]);
                if ($checkEmail->rowCount() > 0) {
                    $error_message = "Cet email est déjà utilisé pour un compte formateur.";
                } else {
                    // Hasher le mot de passe
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Mettre à jour le formateur existant avec le mot de passe et réinitialiser le code d'entrée
                    $stmt = $pdo->prepare("UPDATE formateurs SET nom_prenom = ?, password = ?, statut = 'en_attente', code_entree = NULL WHERE LOWER(email) = LOWER(?)");
                    if ($stmt->execute([$nom_prenom, $hashedPassword, $email])) {
                        if ($stmt->rowCount() > 0) {
                            $success_message = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                            error_log("Inscription réussie pour $email");
                        } else {
                            $error_message = "Aucun formateur trouvé pour cet email ou mise à jour échouée.";
                            error_log("Échec mise à jour formateur pour $email");
                        }
                    } else {
                        $error_message = "Une erreur est survenue lors de la mise à jour. Veuillez réessayer.";
                        error_log("Erreur SQL pour $email: " . print_r($stmt->errorInfo(), true));
                    }
                }
            }
        } catch (PDOException $e) {
            $error_message = "Erreur lors du traitement de l'inscription : " . $e->getMessage();
            error_log("Erreur SQL dans inscription-formateur.php : " . $e->getMessage());
        }
    }
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
    <link rel="stylesheet" href="../asset/css/inscription-formateur.css">
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
    <?php require_once '../components/header.php'?>

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
    <?php require_once '../components/footer.php'?>

    <script src="../asset/js/inscription-formateur.js"></script>
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