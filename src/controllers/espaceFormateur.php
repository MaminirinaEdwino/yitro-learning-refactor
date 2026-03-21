<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/cours.repositories.php";
require_once "./src/repositories/forumMessage.repositories.php";

$espaceFormateur = new Router();

$espaceFormateur->get("/espace/formateur", function () {
    $coursRepo = new CoursRepositories();
    $forumMessageRepo = new ForumMessageRepositories();


    $total_cours = $coursRepo->GetCoursCount($_SESSION['formateur_id']);
    $vente = $coursRepo->GetVente($_SESSION['formateur_id']);
    $apprenants = $coursRepo->GetApprenant($_SESSION['formateur_id']);
    $notifications = $forumMessageRepo->GetNotifications($_SESSION['formateur_id']);
    $progression = [];
    foreach ($apprenants as $a) {
        $progression[$a['id']]['titre'] = $a['titre'];
        $progression[$a['id']]['apprenants'][$a['utilisateur_id']] = [
            'nom' => $a['utilisateur_nom'],
            'progression' => $a['total_modules'] > 0 ? ($a['modules_termines'] / $a['total_modules']) * 100 : 0
        ];
    }
    TemplateRender::render("/espaceformateur/espaceformateur.php", [
        "total_cours"=>$total_cours,
        "ventes"=>$vente,
        "apprenants"=>$apprenants,
        "notifications"=>$notifications,
        "progression"=>$progression
    ]);
});
