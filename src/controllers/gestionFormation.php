<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/formation.repositories.php";
require_once "./src/repositories/formateur.repositories.php";
require_once "./src/repositories/utilisateurs.repositories.php";
require_once "./src/repositories/module.repositories.php";
require_once "./src/repositories/cours.repositories.php";
require_once "./src/repositories/contenueFormation.repositories.php";
require_once "./src/repositories/journalActivite.repositories.php";
require_once "./src/models/journalActivite.php";
require_once "./src/models/contenu_formation.php";
require_once "./src/models/formation.php";

$gestionFormationRouter = new Router();

$gestionFormationRouter->get("/gestion/formation", function () {
    $formationRepo = new FormationRepositories();
    $contenuFormationRepo = new ContenueFormationRepositories();

    $formations = $formationRepo->GetAllByNom();
    $contenuFormation = $contenuFormationRepo->GetSousFormation();

    TemplateRender::render("/admin/gestionFormation.php", [
        "formations" => $formations,
        "sous_formations" => $contenuFormation
    ]);
});

$gestionFormationRouter->post("/formation/new", function () {
    $nom_formation = trim($_POST['nom_formation'] ?? '');
    $formationRepo = new FormationRepositories();
    $journalRepo = new JournalActiviteRepositories();

    $formationRepo->Insert(new Formation($nom_formation));
    $details = "Formation ajoutée: " . $nom_formation;
    $journal = new JournalActivite($_SESSION["user_id"],  'Ajout Formation', $details);
    $journalRepo->Insert($journal);
    header("Location: /gestion/formation");
    exit();
});

$gestionFormationRouter->post("/sousformation/new", function () {
    $formation_id = $_POST['formation_parent_id'] ?? 0;
    $sous_formation_nom = trim($_POST['sous_formation'] ?? '');
    $contenuFormationRepo = new ContenueFormationRepositories();
    $journalRepo = new JournalActiviteRepositories();

    $contenuFormationRepo->Insert(new ContenuFormation($formation_id, $sous_formation_nom));

    $details = "Sous-formation ajoutée: " . $sous_formation_nom . " (Formation ID: " . $formation_id . ")";
    $journalRepo->Insert(new JournalActivite($_SESSION['user_id'], "Ajout sous formation", $details));
    header("Location: /gestion/formation");
    exit();
});

$gestionFormationRouter->get("/gestion/formation/:id", function (int $id)  {
    $formationRepo = new FormationRepositories();
    $contenuFormationRepo = new ContenueFormationRepositories();
    $contenuFormation = $contenuFormationRepo->GetSousFormationAsJson($id);
    $formation_detials = $formationRepo->GetById($id);

    TemplateRender::render("/admin/voirDetailsFormation.php", [
        "id"=>$id,
        "formation_details"=>$formation_detials,
        "sous_formations"=>$contenuFormation
    ]);
});
