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

    public function GetAll(): array {
        $result = [];
        $query = "SELECT * FROM questions";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Quiz {
        $result = [];
        $query = "SELECT * FROM quiz WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$id]);
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    
}