<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/cours.repositories.php";
require_once "./src/repositories/inscription.repositories.php";
require_once "./src/repositories/formateur.repositories.php";
require_once "./src/repositories/module.repositories.php";
require_once "./src/repositories/completions.repositories.php";
require_once "./src/repositories/quiz.repositories.php";
require_once "./src/repositories/requltatQuiz.repositories.php";
require_once "./src/repositories/lecon.repositories.php";

$coursRouter = new Router();

$coursRouter->get("/cours/apprenant/:id", function (int $coursId) {
    $userId = $_SESSION['user_id'];
    $coursRepo = new CoursRepositories();
    $inscriptionrepo = new InscriptionRepositories();
    $formateurRepo = new FormateurRepositories();
    $moduleRepo = new ModuleRepositories();
    $completionRepo = new CompletionsRepositories();
    $leconRepo = new LeconRepositories();
    $quizRepo = new QuizRepositories();
    $resultatQuizRepo = new ResultatQuizRepositories();


    if (!$_SESSION['user_id']) {
        header("location: /connexion");
    }

    $cours = $coursRepo->GetById($coursId);
    if (!$cours) {
        header("Location: espace_apprenant.php");
        exit();
    }
    $isEnrolled = $inscriptionrepo->GetEnrolledCours($userId, $coursId);
    $formateur = $formateurRepo->GetById($cours->getIdFormateur());
    $modules = $moduleRepo->GetByCoursId($cours->getId());
    $completedModule = $completionRepo->GetByCoursUserId($coursId, $userId);
    $lecons = [];
    $quiz = [];
    $completed_quizzes = [];
    foreach ($modules as $module) {
        $lecons[$module->getId()] = $leconRepo->GetByModuleId($module->getId());
        $quiz[$module->getId()] = $quizRepo->GetByModuleId($module->getId());
        $completed_quizzes[$module->getId()] = $resultatQuizRepo->GetByModuleId($userId, $module->getId());
    }
    $is_free = $cours->getPrix() == 0;
    $can_access = $is_free || $isEnrolled;
    TemplateRender::render("/cours/userCoursDetails.php", [
        "is_free"=>$is_free,
        "can_access"=>$can_access,
        "cours"=>$cours,
        "formateur"=>$formateur,
        "completed_module"=>$completedModule,
        "completed_quizzes"=>$completed_quizzes,
        "quiz"=>$quiz,
        "lecons"=>$lecons,
        "modules"=>$modules
    ]);
});
