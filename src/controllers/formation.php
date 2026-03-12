<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/formation.repositories.php";
require_once "./src/repositories/cours.repositories.php";


$formationRouter = new Router();
$formationRouter->get("/formation/catalogue", function(){
    $formationRepo = new FormationRepositories();
    $formations = $formationRepo->GetAll();
    $coursRepo = new CoursRepositories();
    $cours = $coursRepo->GetCoursCatalogue();
    TemplateRender::render("/formations/catalogue.php", ["formations"=>$formations, "cours"=>$cours]);
});