<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/formation.repositories.php";
require_once "./src/repositories/cours.repositories.php";
require_once "./src/repositories/forum.repositories.php";


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
