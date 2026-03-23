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

$gestionFormationRouter->get("/gestion/formation/:id", function (int $id) {
    $formationRepo = new FormationRepositories();
    $contenuFormationRepo = new ContenueFormationRepositories();
    $contenuFormation = $contenuFormationRepo->GetSousFormationAsJson($id);
    $formation_detials = $formationRepo->GetById($id);

    TemplateRender::render("/admin/voirDetailsFormation.php", [
        "id" => $id,
        "formation_details" => $formation_detials,
        "sous_formations" => $contenuFormation
    ]);
});

$gestionFormationRouter->get("/formation/edit/:id", function (int $id_formation) {
    $formationRepo = new FormationRepositories();

    $formation = $formationRepo->GetById($id_formation);
    TemplateRender::render("/admin/editFormation.php", ["formations" => $formation]);
});

$gestionFormationRouter->post("/formation/edit/:id", function (int $id_formation) {
    $nouveau_nom = trim($_POST['nouveau_nom'] ?? '');
    $formationRepo = new FormationRepositories();
    $journalRepo = new JournalActiviteRepositories();

    $formation = $formationRepo->GetById($id_formation);
    $formation->setNom_formation($nouveau_nom);
    $formationRepo->Update($formation);
    $details = "Modification formation principale ID: " . $id_formation . " vers: " . $nouveau_nom;
    $journalRepo->Insert(new JournalActivite($_SESSION['user_id'], 'Modification Formation', $details));
    header("Location: /gestion/formation");
    exit;
});

$gestionFormationRouter->get("/formation/delete/:id",  function (int $id_formation) {
    $formationRepo = new FormationRepositories();
    $journalRepo = new JournalActiviteRepositories();

    $formation = $formationRepo->GetById($id_formation);
    $formationRepo->Delete($formation);
    $journalRepo->Insert(new JournalActivite($_SESSION['user_id'],  "Suppression Formation", "Suppression formaion " . $formation->getNom_formation()));
    header("Location: /gestion/formation");
});

$gestionFormationRouter->get("/contenu/edit/:id", function(int $id_contenu){
    $contenuFormationRepo = new ContenueFormationRepositories();

    TemplateRender::render("/admin/editContenu.php", [
        "contenu"=>$contenuFormationRepo->GetContenuFormation($id_contenu)
    ]);
});

$gestionFormationRouter->post("/contenu/edit/:id", function (int $id_contenu) {
    $nouveau_nom = trim($_POST['nouveau_nom'] ?? '');
    $parent_id = $_POST['formation_parent_id'] ?? 0;
    $contenuRepo = new ContenueFormationRepositories();
    $journalRepo = new JournalActiviteRepositories();
    $contenu = $contenuRepo->GetById($id_contenu);
    $contenu->setSousFormation($nouveau_nom);
    $contenuRepo->Update($contenu);
    $journalRepo->Insert(new JournalActivite($_SESSION['user_id'], "modification sous formation", "Modifiation du sous formation ".$nouveau_nom));
    header("Location: /gestion/formation/".$parent_id);
    exit();
});

$gestionFormationRouter->get("/contenu/delete/:id", function(int $id_contenu){
    $contenuFormationRepo = new ContenueFormationRepositories();
    $contenu = $contenuFormationRepo->GetById($id_contenu);
    $contenuFormationRepo->Delete($contenu);
    header("Location: /gestion/formation/".$contenu->getIdFormation());
    exit();
});