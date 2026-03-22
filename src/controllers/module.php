<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/module.repositories.php";
require_once "./src/repositories/completions.repositories.php";
require_once "./src/repositories/lecon.repositories.php";

$moduleRouter = new Router();
$moduleRouter->get("/module/edit/:id", function (int $moduleId) {
    $moduleRepo = new ModuleRepositories();
    $module = $moduleRepo->GetById($moduleId);

    TemplateRender::render("/modules/edit.php", [
        "id" => $moduleId,
        "module" => $module
    ]);
});

$moduleRouter->post("/module/edit/:id", function (int $moduleId) {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $moduleRepo = new ModuleRepositories();

    $module = $moduleRepo->GetById($moduleId);
    $module->setTitre($titre);
    $module->setDescription($description);
    $moduleRepo->Update($module);
    header("Location: /cours/formateur");
    exit;
});

$moduleRouter->get("/module/delete/:id", function (int $moduleId) {
    $completionRepo = new CompletionsRepositories();
    $leconRepo = new LeconRepositories();
    $moduleRepo = new ModuleRepositories();
    $module = $moduleRepo->GetById($moduleId);
    // Supprimer les enregistrements de la table completions liés à ce module
    $completionRepo->DeleteByModueId($moduleId);

    // Supprimer les leçons liées au module
    $leconRepo->DeleteByModuleId($moduleId);

    // Supprimer le module
    $moduleRepo->Delete($module);

    header("Location: /cours/formateur");
    exit;
});

$moduleRouter->get("/module/cours/:id", function (int $cours_id) {
    $moduleRepo = new ModuleRepositories();
    $modules = $moduleRepo->GetByCoursIdArray($cours_id);

    // echo $modules[0]["id"];
    header('Content-Type: application/json');
    echo json_encode($modules);
});
