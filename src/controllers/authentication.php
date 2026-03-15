<?php


require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/utilisateurs.repositories.php";
require_once "./src/config/database.php";

$authRouter = new Router();

$authRouter->get("/connexion", function () {
    TemplateRender::render("/authentication/connexion.php", null);
});

$authRouter->get("/inscription", function () {
    TemplateRender::render("/authentication/inscription.php", null);
});

$authRouter->get("/logout", function () {
    $_SESSION = [];

    session_destroy();

    header("Location: /connexion");
    exit;
});

$authRouter->post("/auth", function () {
    $userRepo = new UtilisateursRepositories();
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password'] ?? '');

    // Validation des champs
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        header("Location: connexion.php");
        exit();
    }

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Adresse e-mail invalide.";
        header("Location: connexion.php");
        exit();
    }

    // Vérification des tentatives de connexion
    $max_attempts = 5;
    $lockout_time = 15 * 60; // 15 minutes en secondes
    if (isset($_SESSION['login_attempts'][$email]) && $_SESSION['login_attempts'][$email]['count'] >= $max_attempts) {
        if (time() - $_SESSION['login_attempts'][$email]['time'] < $lockout_time) {
            $_SESSION['error'] = "Trop de tentatives de connexion. Veuillez réessayer dans "
                . ceil(($lockout_time - (time() - $_SESSION['login_attempts'][$email]['time'])) / 60) . " minutes.";
            header("Location: connexion.php");
            exit();
        } else {
            // Réinitialiser les tentatives après le temps de verrouillage
            unset($_SESSION['login_attempts'][$email]);
        }
    }

    // Requête pour vérifier l'utilisateur avec le rôle 'apprenant' et compte actif

    $user = $userRepo->GetForAuth($email, $password);

    // Vérification des identifiants
    if ($user) {
        if ($user->getActif() == false) {
            error_log("Compte inactif pour l'email: " . $email);
            $_SESSION['error'] = "Votre compte est désactivé. Veuillez contacter l'administrateur. " . $user->getActif();
            header("Location: /connexion");
            exit();
        }

        if (password_verify($password, $user->getMdp())) {
            // Réinitialiser les tentatives de connexion en cas de succès
            unset($_SESSION['login_attempts'][$email]);

            // Création de la session
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_type'] = 'apprenant';
            $_SESSION['user_nom'] = $user->getNom();
            $_SESSION['success'] = "Connexion réussie ! Bienvenue, " . $user->getNom() . ".";
            error_log("Connexion réussie pour l'utilisateur: " . $email);
            header("Location: ../Espace/apprenant/espace_apprenant.php");
            exit();
        } else {
            // Enregistrer la tentative échouée
            if (!isset($_SESSION['login_attempts'][$email])) {
                $_SESSION['login_attempts'][$email] = ['count' => 0, 'time' => time()];
            }
            $_SESSION['login_attempts'][$email]['count']++;
            $_SESSION['login_attempts'][$email]['time'] = time();

            error_log("Mot de passe incorrect pour l'email: " . $email);
            $_SESSION['error'] = "Mot de passe incorrect. Tentatives restantes : "
                . ($max_attempts - $_SESSION['login_attempts'][$email]['count']) . ".";
            header("Location: /connexion");
            exit();
        }
    } else {
        // Enregistrer la tentative échouée (email non trouvé ou rôle incorrect)
        if (!isset($_SESSION['login_attempts'][$email])) {
            $_SESSION['login_attempts'][$email] = ['count' => 0, 'time' => time()];
        }
        $_SESSION['login_attempts'][$email]['count']++;
        $_SESSION['login_attempts'][$email]['time'] = time();

        error_log("Échec de connexion pour l'email: " . $email);
        $_SESSION['error'] = "E-mail non trouvé ou compte non autorisé. Tentatives restantes : " . ($max_attempts - $_SESSION['login_attempts'][$email]['count']) . ".";
        header("Location: /connexion");
        exit();
    }
});
