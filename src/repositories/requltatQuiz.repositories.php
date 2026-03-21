<?php

use LDAP\Result;

require_once "./src/config/database.php";
require_once "./src/models/resultatQuiz.php";

class ResultatQuizRepositories
{
    private Database $database;
    private array $result;

    public function __construct()
    {
        $this->database = new Database();
    }
    private function PushArray($stmt, $result)
    {
        $this->result = [];
        while ($donne = $stmt->fetch()) {
            $var = new ResultatQuiz(
                $donne["utilisateur_id"],
                $donne["quiz_id"],
                $donne["score"]
            );
            $var->setId($donne["id"]);
            $var->setDate($donne["date"]);
            array_push($this->result, $var);
        }
    }

    public function Insert(ResultatQuiz $resultatQuiz)
    {
        $query = "INSERT INTO resultats_quiz(utilisateur_id, quiz_id, score) VALUES(:utilisateur_id, :quiz_id, :score)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "utilisateur_id" => $resultatQuiz->getUtilisateurId(),
            "quiz_id" => $resultatQuiz->getQuizId(),
            "score" => $resultatQuiz->getScore()
        ]);
    }

    public function GetAll(): array
    {
        $result = [];
        $query = "SELECT * FROM resultats_quiz";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Quiz
    {
        $result = [];
        $query = "SELECT * FROM resultats_quiz WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $id]);
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
            "score" => $resultatQuiz->getScore(),
            "id" => $resultatQuiz->getId()
        ]);
    }

    public function Delete(ResultatQuiz $resultatQuiz)
    {
        $query = "DELETE FROM resultats_quiz WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $resultatQuiz->getId()
        ]);
    }

    public function GetByModuleId(int $utilisateurId, $moduleId): array
    {
        $stmt = $this->database->getConnection()->prepare("SELECT quiz_id FROM resultats_quiz WHERE utilisateur_id = ? AND quiz_id IN (SELECT id FROM quiz WHERE module_id = ?)");
        $stmt->execute([$utilisateurId, $moduleId]);
        $this->result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $this->result;
    }

    public function GetByUserIdCoursId(int $userId, int $coursId): array
    {
        $stmt = $this->database->getConnection()->prepare("
            SELECT q.titre AS quiz_titre, r.score, r.date, q.score_minimum
            FROM resultats_quiz r
            JOIN quiz q ON r.quiz_id = q.id
            JOIN modules m ON q.module_id = m.id
            WHERE r.utilisateur_id = ? AND m.cours_id = ?
        ");
        $stmt->execute([$userId, $coursId]);
        $resultats_quiz = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultats_quiz;
    }
}
