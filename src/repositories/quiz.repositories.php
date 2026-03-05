<?php

require_once "./src/config/database.php";
require_once "./src/models/quiz.php";

class QuizRepositories{
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new Quiz(
                $donne["module_id"],
                $donne["titre"],
                $donne["description"],
                $donne["score_minimum"]
            );
            $var->setId($donne["id"]);
            array_push($result, $var);
        }
    }

    public function Insert(Quiz $quiz) {
        $query = "INSERT INTO quiz(module_id, titre, description, score_minimum) VALUES(:module_id, :titre, :description, :score_minimum)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "module_id"=>$quiz->getModuleId(),
            "titre"=>$quiz->getTitre(),
            "description"=>$quiz->getDescription(),
            "score_minimum"=>$quiz->getScoreMinimum()
        ]);
    }
}