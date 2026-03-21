<?php

require_once "./src/router/router.php";
require_once "./src/repositories/contenueFormation.repositories.php";

$sousFormationRouter = new Router();
$sousFormationRouter->get("/sousformation/:id", function(int $formationId){
    $sousFormationRepo = new ContenueFormationRepositories();
    $sousFormation = $sousFormationRepo->GetSousFormationAsJson($formationId);

    echo json_encode($sousFormation);
});