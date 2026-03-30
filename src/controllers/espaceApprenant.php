<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/formation.repositories.php";
require_once "./src/repositories/cours.repositories.php";
require_once "./src/repositories/forum.repositories.php";
require_once "./src/repositories/inscription.repositories.php";


$espaceApprenantRouter = new Router();

$espaceApprenantRouter->get("/espace/apprenant", function () {
    $formationRepo = new FormationRepositories();
    $formations = $formationRepo->GetAllByNom();
    $coursRepo = new CoursRepositories();
    $cours = $coursRepo->GetCoursFormation();
    $forumRepositories = new ForumRepositories();
    $forums = $forumRepositories->GetByCours();
    TemplateRender::render("/espaceApprenant/espaceApprenant.php", [
        "formations" => $formations,
        "cours" => $cours,
        "forums" => $forums
    ]);
});

$espaceApprenantRouter->get("/espace/apprenant/progression",  function () {
    if (!$_SESSION['logged_in']) {
        header("location: /connexion");
    }
    $coursRepo = new CoursRepositories();
    $cours = $coursRepo->GetCoursProgression();
    TemplateRender::render("/espaceApprenant/progression.php", ['cours' => $cours]);
});

$espaceApprenantRouter->get("/espace/apprenant/cours", function () {
    if (!$_SESSION['logged_in']) {
        header("location: /connexion");
    }
    $coursRepo = new CoursRepositories();
    $cours = $coursRepo->GetByUser($_SESSION['user_id']);
    $coursStatus = $coursRepo->GetCoursStatus($cours, $_SESSION['user_id']);
    TemplateRender::render("/espaceApprenant/mesCours.php", [
        'cours_status' => $coursStatus,
        "cours" => $cours
    ]);
});

$espaceApprenantRouter->post("/enroll/cours", function () {
    $inscriptionRepo = new InscriptionRepositories();
    header('Content-Type: application/json');
    $utilisateur_id = $_SESSION['user_id'];
    $cours_id = $_POST['cours_id'] ?? null;

    $enrolled = $inscriptionRepo->GetEnrolledCours($utilisateur_id, $cours_id);

    if ($enrolled) {
        echo json_encode(['success' => false, 'message' => 'Vous êtes déjà inscrit à ce cours.']);
        exit();
    }

    $inscriptionRepo->Insert(new Inscription($utilisateur_id, $cours_id, "paye"));
    echo json_encode(['success' => true, 'message' => 'Inscription réussie !']);
});

$espaceApprenantRouter->post("/mvola", function () {
    if (!AuthChecker("apprenant")) {
        header("Location: /connexion");
    }
    $inscriptionRepo = new InscriptionRepositories();
    // Le script doit retourner une réponse JSON
    header('Content-Type: application/json');

    // Récupérer les données envoyées par le formulaire
    $utilisateur_id = $_SESSION['user_id'];
    $cours_id = $_POST['cours_id'] ?? null;
    $prix_cours = $_POST['prix_cours'] ?? null;
    $mvola_number = $_POST['mvola_number'] ?? null;

    // Validation des données
    if (empty($mvola_number) || empty($cours_id) || empty($prix_cours)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Données de paiement manquantes.']);
        exit();
    }

    try {
        $count = $inscriptionRepo->GetUserCoursInscription($utilisateur_id, $cours_id);
        if ($count > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Vous êtes déjà inscrit à ce cours ou une transaction est en cours.']);
            exit();
        }
        $inscription_id = $inscriptionRepo->Insert(new Inscription($utilisateur_id, $cours_id, "en_attente"));

        // $transaction_reference = uniqid('mvola_'); // Une référence unique pour la transaction

        // Données de la transaction pour l'API Mvola
        $api_call_success = true; // Simule un appel réussi
        // ... Le code cURL réel pour l'appel à l'API Mvola ...
        $inscription = $inscriptionRepo->GetById($inscription_id);
        if ($api_call_success) {

            $inscription->setStatutPayement("paye");
            $inscriptionRepo->Update($inscription);

            echo json_encode([
                'status' => 'success',
                'message' => 'Transaction initiée. Veuillez valider le paiement sur votre téléphone.',
            ]);
        } else {
            // Erreur de l'API Mvola
            // Si l'API renvoie une erreur, il faut annuler l'inscription "en attente"
            $inscriptionRepo->Delete($inscription);
            echo json_encode([
                'status' => 'error',
                'message' => 'Erreur lors de l\'initialisation du paiement.',
            ]);
        }
    } catch (PDOException $e) {
        // Gérer les erreurs de base de données
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
    }
});
