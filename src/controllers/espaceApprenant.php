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
    if (!AuthChecker("apprenant")) {
        header("Location: /connexion");
    }
    $inscriptionRepo = new InscriptionRepositories();
    header('Content-Type: application/json');
    $utilisateur_id = $_SESSION['user_id'];
    $cours_id = $_POST['cours_id'] ?? null;

    if (!$cours_id) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'ID du cours manquant.']);
        exit();
    }
    $enrolled = $inscriptionRepo->GetEnrolledCours($utilisateur_id, $cours_id);

    if ($enrolled) {
        echo json_encode(['success' => false, 'message' => 'Vous êtes déjà inscrit à ce cours.']);
        exit();
    }

    $inscriptionRepo->Insert(new Inscription($utilisateur_id, $cours_id, "paye"));
    echo json_encode(['success' => true, 'message' => 'Inscription réussie !']);
});
