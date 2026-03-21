<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/module.repositories.php";

$moduleRouter = new Router();
$moduleRouter->get("/module/edit/:id", function (int $moduleId) {
    $moduleRepo = new ModuleRepositories();
    $module = $moduleRepo->GetById($moduleId);

    TemplateRender::render("/modules/edit.php", [
        "id"=>$moduleId,
        "module"=>$module
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
