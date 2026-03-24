<?php
require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/utilisateurs.repositories.php";

$signinRouter = new Router();
$signinRouter->get("/signin/apprenant", function () {
    TemplateRender::render("/signin/apprenant.php", null);
});

$signinRouter->post("/signin/apprenant", function () {
    $userRepo = new UtilisateursRepositories();
    // Validation des champs obligatoires
    if (empty($_POST['nom']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['pays']) || empty($_POST['langue']) || empty($_POST['type_cours']) || empty($_POST['niveau_formation'])) {
        echo "<script>alert('Erreur : Tous les champs obligatoires doivent être remplis.');</script>";
        exit;
    }

    // Nettoyage des entrées
    $nom = trim($_POST['nom']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mot_de_passe = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telephone = trim($_POST['telephone']);
    $pays = trim($_POST['pays']);
    $langue = trim($_POST['langue']);
    $autre_langue = trim($_POST['autre_langue']);
    $type_cours = trim($_POST['type_cours']);
    $niveau_formation = trim($_POST['niveau_formation']);
    $niveau_etude = trim($_POST['niveau_etude']);
    $acces_internet = trim($_POST['acces_internet']);
    $appareil = isset($_POST['appareil']) ? trim($_POST['appareil']) : '';
    $accessibilite = trim($_POST['accessibilite']);
    $rgpd = isset($_POST['rgpd']) ? 1 : 0;
    $charte = isset($_POST['charte']) ? 1 : 0;

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Erreur : Adresse e-mail invalide.');</script>";
        exit;
    }

    // Vérifier si l'email existe déjà
    $userExits = $userRepo->GetbyEmail($email);
    if ($userExits) {
        echo "<script>alert('Erreur : Cet e-mail est déjà utilisé.');</script>";
        exit;
    }

    // Gestion des objectifs
    $objectifs = isset($_POST['objectifs']) ? implode(", ", $_POST['objectifs']) : '';

    // Gestion de l'upload de la photo
    $photo_path = "";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "Uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $maxFileSize = 5 * 1024 * 1024; // 5 Mo
        if ($_FILES['photo']['size'] > $maxFileSize) {
            echo "<script>alert('Erreur : La taille du fichier dépasse la limite de 5 Mo.');</script>";
            exit;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
            echo "<script>alert('Erreur : Seuls les formats JPEG, PNG et GIF sont autorisés.');</script>";
            exit;
        }

        $photo_name = basename($_FILES["photo"]["name"]);
        $photo_path = $target_dir . time() . "_" . $photo_name;

        if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_path)) {
            echo "<script>alert('Erreur : Échec du téléchargement de la photo.');</script>";
            exit;
        }
    }
    
    if ($userRepo->Insert(new Utilisateur(
        $nom,
        $email,
        $mot_de_passe,
        $telephone,
        $photo_path,
        $pays,
        $langue,
        $autre_langue,
        $objectifs,
        $type_cours,
        $niveau_formation,
        $niveau_etude,
        $acces_internet,
        $appareil,
        $accessibilite,
        $rgpd,
        $charte,
        "apprenant"
    ))) {
        echo "<script>window.location.href='merci.php';</script>";
    } else {
        error_log("Erreur SQL ");
        echo "<script>alert('Une erreur est survenue lors de l\'inscription. Veuillez réessayer.');</script>";
    }
});

$signinRouter->get("/merci", function(){
    TemplateRender::render("/signin/merci.php", null);
});
