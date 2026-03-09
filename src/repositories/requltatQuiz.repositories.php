<?php

use LDAP\Result;

require_once "./src/config/database.php";
require_once "./src/models/resultatQuiz.php";

class ResultatQuizRepositories {
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new ResultatQuiz(
                $donne["utilisateur_id"],
                $donne["quiz_id"],
                $donne["score"]
            );
            $var->setId($donne["id"]);
            $var->setDate($donne["date"]);
            array_push($result, $var);
        }
    }

     public function Insert(ResultatQuiz $resultatQuiz) {
        $query = "INSERT INTO resultats_quiz(utilisateur_id, quiz_id, score) VALUES(:utilisateur_id, :quiz_id, :score)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "utilisateur_id"=>$resultatQuiz->getUtilisateurId(),
            "quiz_id"=>$resultatQuiz->getQuizId(),
            "score"=>$resultatQuiz->getScore()
        ]);
    }

    public function GetAll(): array {
        $result = [];
        $query = "SELECT * FROM resultats_quiz";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Quiz {
        $result = [];
        $query = "SELECT * FROM resultats_quiz WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$id]);
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function Update(ResultatQuiz $resultatQuiz)
    {
        $query = "UPDATE resultats_quiz SET utilisateur_id=:utilisateur_id, quiz_id=:quiz_id, score=:score WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "utilisateur_id" => $resultatQuiz->getUtilisateurId(),
            "quiz_id" => $resultatQuiz->getQuizId(),
            "score" => $resultatQuiz->getScore()
        ]);
    }

    public function Delete(Post $post)
    {
        $query = "DELETE FROM resultats_quiz WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $post->getId()
        ]);
    }
}