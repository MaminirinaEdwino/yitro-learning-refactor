<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/cours.repositories.php";
require_once "./src/repositories/forumMessage.repositories.php";
require_once "./src/repositories/module.repositories.php";
require_once "./src/repositories/inscription.repositories.php";
require_once "./src/repositories/requltatQuiz.repositories.php";

$espaceFormateurRouter = new Router();

$espaceFormateurRouter->get("/espace/formateur", function () {
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
        "total_cours" => $total_cours,
        "ventes" => $vente,
        "apprenants" => $apprenants,
        "notifications" => $notifications,
        "progression" => $progression
    ]);
});

$espaceFormateurRouter->get("/espace/formateur/progression", function () {
    $coursRepo = new CoursRepositories();
    $moduleRepo = new ModuleRepositories();
    $inscriptionRepo = new InscriptionRepositories();
    $resultatsQuizRepo = new ResultatQuizRepositories();

    $cours = $coursRepo->GetCoursByFormateur($_SESSION["formateur_id"]);
    $progression = [];
    foreach ($cours as $c) {
        // Récupérer les modules du cours
        $modules = $moduleRepo->GetByCoursId($c["id"]);
        $total_modules = count($modules);
        $apprenants = $inscriptionRepo->GetApprenantByCoursId($c['id']);

        $progression[$c['id']] = [
            'titre' => $c['titre'],
            'total_modules' => $total_modules,
            'apprenants' => []
        ];

        foreach ($apprenants as $apprenant) {
            // Récupérer les modules terminés
            $modules_termines = $moduleRepo->GetModuletermine($apprenant["utilisateur_id"], $c["id"]);

            $resultats_quiz = $resultatsQuizRepo->GetByUserIdCoursId($apprenant['utilisateur_id'], $c['id']);

            $progression[$c['id']]['apprenants'][$apprenant['utilisateur_id']] = [
                'nom' => $apprenant['utilisateur_nom'],
                'modules_termines' => $modules_termines,
                'progression' => $total_modules > 0 ? (count($modules_termines) / $total_modules) * 100 : 0,
                'resultats_quiz' => $resultats_quiz
            ];
        }
    }

    TemplateRender::render("/espaceformateur/progressionApprenant.php", [
        "cours" => $cours,
        "progression" => $progression
    ]);
});
