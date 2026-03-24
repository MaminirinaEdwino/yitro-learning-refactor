<?php
require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/utilisateurs.repositories.php";
require_once "./src/repositories/formateur.repositories.php";

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
        header("Location: /merci");
        exit();
    } else {
        error_log("Erreur SQL ");
        echo "<script>alert('Une erreur est survenue lors de l\'inscription. Veuillez réessayer.');</script>";
    }
});

$signinRouter->get("/merci", function () {
    TemplateRender::render("/signin/merci.php", null);
});

$signinRouter->get("/signin/formateur", function () {

    TemplateRender::render("/signin/formateur.php", null);
});

$signinRouter->post("/signin/formateur", function () {
    $formateurRepo =  new FormateurRepositories();
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
    $error_message = "";
    // Vérifications
    if (empty($nom_prenom) || empty($email) || empty($password) || empty($confirmPassword) || empty($entryCode)) {
        $error_message = "Tous les champs sont requis.";
    } elseif ($password !== $confirmPassword) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "L'email n'est pas valide.";
    } else {
        try {
            $checkCode = $formateurRepo->CheckCode($email, $entryCode);
            // Vérifier si le code d'entrée est valide pour cet email
            if ($checkCode->rowCount() === 0) {
                $error_message = "Code d'entrée invalide ou email non autorisé.";
                error_log("Échec validation - Email: $email, Code: $entryCode, Résultat: aucun formateur trouvé");
            } else {
                // Vérifier si l'email est déjà utilisé par un formateur avec un mot de passe (inscription complète)
                $checkEmail = $formateurRepo->CheckFormateur($email);
                if ($checkEmail->rowCount() > 0) {
                    $error_message = "Cet email est déjà utilisé pour un compte formateur.";
                } else {
                    // Hasher le mot de passe
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Mettre à jour le formateur existant avec le mot de passe et réinitialiser le code d'entrée
                    $stmt = $formateurRepo->ResetCodeStmt();
                    if ($stmt->execute([$nom_prenom, $hashedPassword, $email])) {
                        if ($stmt->rowCount() > 0) {
                            $_SESSION["inscirption_formateur_ok"] = "Inscription réussie. Vous pouvez maintenant vous connecter.";

                            error_log("Inscription réussie pour $email");
                            header("Location: /connexion");
                        } else {
                            $_SESSION["erreur"] = "Aucun formateur trouvé pour cet email ou mise à jour échouée.";
                            error_log("Échec mise à jour formateur pour $email");
                            header("Location: /signin/formateur");
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
});

$signinRouter->post("/signin/postuler", function () {
    $formateurRepo = new FormateurRepositories();
    function sanitize($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $nom_prenom = sanitize($_POST['nom_prenom'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $telephone = sanitize($_POST['telephone'] ?? '');
    $ville_pays = sanitize($_POST['ville_pays'] ?? '');
    $linkedin = sanitize($_POST['linkedin'] ?? '');

    $intitule_metier = sanitize($_POST['intitule_metier'] ?? '');
    $experience_formation = sanitize($_POST['experience_formation'] ?? '');
    $detail_experience = sanitize($_POST['detail_experience'] ?? '');

    // Upload du fichier
    $cv_nom = '';
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $cv_tmp = $_FILES['cv']['tmp_name'];
        $cv_nom = uniqid() . '_' . basename($_FILES['cv']['name']);
        move_uploaded_file($cv_tmp, './Uploads/cv/' . $cv_nom);
    }

    $categories = isset($_POST['categories']) && is_array($_POST['categories']) ? implode(", ", $_POST['categories']) : '';
    $autre_domaine = sanitize($_POST['autre_domaine'] ?? '');

    $titre_cours = sanitize($_POST['titre_cours'] ?? '');
    $objectif = sanitize($_POST['objectif'] ?? '');
    $public_cible = sanitize($_POST['public_cible'] ?? '');
    $detail_complementaire = sanitize($_POST['detail_complementaire'] ?? '');
    $formats = isset($_POST['formats']) && is_array($_POST['formats']) ? implode(", ", $_POST['formats']) : '';
    $format_autre = sanitize($_POST['format_autre'] ?? '');
    $duree_estimee = sanitize($_POST['duree_estimee'] ?? '');
    $type_formation = sanitize($_POST['type_formation'] ?? '');

    $motivation = sanitize($_POST['motivation'] ?? '');
    $valeurs = isset($_POST['valeurs']) && is_array($_POST['valeurs']) ? implode(", ", $_POST['valeurs']) : '';
    $profil_public = sanitize($_POST['profil_public'] ?? '');

    $formateurRepo->Insert(new Formateur(
        $nom_prenom,
        $email,
        $telephone,
        $ville_pays,
        $linkedin,
        $intitule_metier,
        $experience_formation,
        $detail_experience,
        $cv_nom,
        $categories,
        $autre_domaine,
        $titre_cours,
        $objectif,
        $public_cible,
        $detail_complementaire,
        $formats,
        $format_autre,
        $duree_estimee,
        $type_formation,
        $motivation,
        $valeurs,
        $profil_public,
        "en_attente"
    ));

    echo "<script>alert('Formulaire soumis avec succès');</script>";
    header("Location: /signin/formateur");
});

$signinRouter->get("/signin/postuler", function(){
    TemplateRender::render("/signin/postulerformateur.php", null);
});
