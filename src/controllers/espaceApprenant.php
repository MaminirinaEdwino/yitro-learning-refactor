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
    // header('Content-Type: application/json');
    $utilisateur_id = $_SESSION['user_id'];
    $cours_id = $_POST['cours_id'] ?? null;
    $reference = $_POST['references_payement'];
    $method = $_POST['method_payement'];

    $enrolled = $inscriptionRepo->GetEnrolledCours($utilisateur_id, $cours_id);

    if ($enrolled) {
        echo json_encode(['success' => false, 'message' => 'Vous êtes déjà inscrit à ce cours.']);
        exit();
    }

    $inscriptionRepo->Insert(new Inscription($utilisateur_id, $cours_id, "en_attente", $reference, $method));
    // echo json_encode(['success' => true, 'message' => 'Inscription réussie !']);
    header("Location: /cours/apprenant/".$cours_id);
});

