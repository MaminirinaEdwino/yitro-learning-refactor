<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/quiz.repositories.php";
require_once "./src/repositories/question.repositories.php";
require_once "./src/repositories/inscription.repositories.php";
require_once "./src/repositories/question.repositories.php";
require_once "./src/repositories/cours.repositories.php";
require_once "./src/repositories/module.repositories.php";
require_once "./src/models/quiz.php";
require_once "./src/models/question.php";

$quizRouter = new Router();

$quizRouter->get("/cours/quiz/apprenant/:id", function (int $quiz_id) {
    $inscriptionRepo = new InscriptionRepositories();
    $quizRepo = new QuizRepositories();
    $questionRepo = new QuestionRepositories();


    $quiz = $quizRepo->GetCoursModuleQuizByQuiz($quiz_id);
    $is_enrolled = $inscriptionRepo->GetEnrolledCours($_SESSION['user_id'], $quiz["cours_id"]);
    $questions = $questionRepo->GetByQuizId($quiz_id);
    TemplateRender::render("/quiz/takequiz.php", [
        "is_enrolled" => $is_enrolled,
        "quiz" => $quiz,
        "questions" => $questions,
        "quiz_id" => $quiz_id
    ]);
});

$quizRouter->get("/quiz/formateur", function () {
    $fomateurId = $_SESSION['formateur_id'];
    $quizRepo = new QuizRepositories();

    $quiz = $quizRepo->GetCoursQuizByFormateurId($fomateurId);


    TemplateRender::render("/quiz/list.php", [
        "quiz" => $quiz
    ]);
});

$quizRouter->get("/quiz/formateur/delete/:id", function (int $quizId) {
    $quizRepo = new QuizRepositories();
    $quiz = $quizRepo->GetById($quizId);

    $quizRepo->Delete($quiz);
    header("Location: /quiz/formateur");
    exit;
});

$quizRouter->get("/quiz/new", function () {
    $coursRepo = new CoursRepositories();

    $cours = $coursRepo->GetCoursByFormateur($_SESSION["formateur_id"]);


    TemplateRender::render("/quiz/create.php", [
        "cours" => $cours
    ]);
});

$quizRouter->post("/quiz/new", function () {
    $module_id = $_POST['module_id'];
    $titre = $_POST['titre_quiz'];
    $description = $_POST['description_quiz'];
    $score_minimum = $_POST['score_minimum'];

    $quizRepo = new QuizRepositories();
    $questionRepo = new QuestionRepositories();
    $quiz = new Quiz(
        $module_id,
        $titre,
        $description,
        $score_minimum
    );
    $quiz_id = $quizRepo->Insert($quiz);
    if (isset($_POST['questions'])) {
        foreach ($_POST['questions'] as $question) {
            $newQuestion = new Question(
                $quiz_id,
                $question['texte'],
                $question['reponse_correcte'],
                $question['reponse_incorrecte_1'],
                $question['reponse_incorrecte_2'],
                $question['reponse_incorrecte_3']
            );
            $questionRepo->Insert($newQuestion);
        }
    }

    header("Location: /quiz/formateur");
    exit;
});

$quizRouter->get("/quiz/edit/:id", function (int $quizId) {
    $formateurId = $_SESSION["formateur_id"];
    $quizRepo = new QuizRepositories();
    $questionRepo = new QuestionRepositories();
    $coursRepo = new CoursRepositories();
    $moduleRepo = new ModuleRepositories();

    $quiz =  $quizRepo->GetQuizByIdFormateurId($quizId, $formateurId);
    $questions = $questionRepo->GetByQuizId($quizId);
    $cours = $coursRepo->GetCoursByFormateur($formateurId);
    $module = $moduleRepo->GetByCoursId($quiz["cours_id"]);

    TemplateRender::render("/quiz/edit.php", [
        "quiz_id" => $quizId,
        "quiz" => $quiz,
        "questions" => $questions,
        "cours" => $cours,
        "module" => $module
    ]);
});

$quizRouter->post("/quiz/edit/:id", function (int $quizId) {
    $module_id = $_POST['module_id'];
    $titre = $_POST['titre_quiz'];
    $description = $_POST['description_quiz'];
    $score_minimum = $_POST['score_minimum'];
    $quizRepo = new QuizRepositories();
    $questionRepo = new QuestionRepositories();

    $quiz = $quizRepo->GetById($quizId);
    $quiz->setModuleId($module_id);
    $quiz->setTitre($titre);
    $quiz->setDescription($description);
    $quiz->setScoreMinimum($score_minimum);
    $quizRepo->Update($quiz);
    $questionRepo->DeleteByQuiId($quizId);

    // Insérer les nouvelles questions
    if (isset($_POST['questions'])) {
        foreach ($_POST['questions'] as $question) {
            $newQuestion = new Question(
                $quizId,
                $question['texte'],
                $question['reponse_correcte'],
                $question['reponse_incorrecte_1'],
                $question['reponse_incorrecte_2'],
                $question['reponse_incorrecte_3']
            );
            $questionRepo->Insert($newQuestion);
        }
    }

    header("Location: /quiz/formateur");
    exit;
});
