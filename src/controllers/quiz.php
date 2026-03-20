<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/quiz.repositories.php";
require_once "./src/repositories/question.repositories.php";
require_once "./src/repositories/inscription.repositories.php";

$quizRouter = new Router();

$quizRouter->get("/cours/quiz/apprenant/:id", function(int $quiz_id){
    $inscriptionRepo = new InscriptionRepositories();
    $quizRepo = new QuizRepositories();
    $questionRepo = new QuestionRepositories();

    
    $quiz = $quizRepo->GetCoursModuleQuizByQuiz($quiz_id);
    $is_enrolled = $inscriptionRepo->GetEnrolledCours($_SESSION['user_id'], $quiz["cours_id"]);
    $questions = $questionRepo->GetByQuizId($quiz_id); 
    TemplateRender::render("/quiz/takequiz.php", [
        "is_enrolled"=>$is_enrolled,
        "quiz"=>$quiz,
        "questions"=>$questions,
        "quiz_id"=>$quiz_id
    ]);
});
