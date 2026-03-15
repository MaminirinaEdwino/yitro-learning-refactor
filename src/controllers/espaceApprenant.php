<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/formation.repositories.php";
require_once "./src/repositories/cours.repositories.php";

$espaceApprenantRouter = new Router();

$espaceApprenantRouter->get("/espace/apprenant", function () {
    $formations = new FormationRepositories()->GetAllByNom();
    $cours = new CoursRepositories()->GetCoursFormation();
    TemplateRender::render("/espaceApprenant/espaceApprenant.php", [
        "formations" => $formations,
        "cours" => $cours
    ]);
});
