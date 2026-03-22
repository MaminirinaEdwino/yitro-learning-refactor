<?php

require_once "./src/config/database.php";
require_once "./src/models/question.php";

class QuestionRepositories
{
    private Database $database;
    private array $result;
    public function __construct()
    {
        $this->database = new Database();
    }
    private function PushArray($stmt, &$result)
    {
        $this->result = [];
        while ($donne = $stmt->fetch()) {
            $var = new Question(
                $donne["quiz_id"],
                $donne["texte"],
                $donne["reponse_correcte"],
                $donne["reponse_incorrecte_1"],
                $donne["reponse_incorrecte_2"],
                $donne["reponse_incorrecte_3"]
            );
            $var->setId($donne["id"]);
            array_push($result, $var);
        }
    }

    public function Insert(Question $question)
    {
        $query = "INSERT INTO questions(quiz_id, texte, reponse_correcte, reponse_incorrecte_1,reponse_incorrecte_2, reponse_incorrecte_3) VALUES(:quiz_id, :texte, :reponse_correcte, :reponse_incorrecte_1, :reponse_incorrecte_2, :reponse_incorrecte_3)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "quiz_id" => $question->getQuizId(),
            "texte" => $question->getTexte(),
            "reponse_correcte" => $question->getReponseCorrecte(),
            "reponse_incorrecte_1" => $question->getReponseIncorrecte1(),
            "reponse_incorrecte_2" => $question->getReponseIncorrecte2(),
            "reponse_incorrecte_3" => $question->getReponseIncorrecte3()
        ]);
    }

    public function GetAll(): array
    {
        $result = [];
        $query = "SELECT * FROM questions";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Question
    {
        $result = [];
        $query = "SELECT * FROM questions WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $id]);
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function Update(Question $question)
    {
        $query = "UPDATE questions SET quiz_id=:quiz_id, texte=:texte, reponse_correcte=:reponse_correcte, reponse_incorrecte_1=:reponse_incorrecte_1,reponse_incorrecte_2=:reponse_incorrecte_2, reponse_incorrecte_3=:reponse_incorrecte_3 WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "quiz_id" => $question->getQuizId(),
            "texte" => $question->getTexte(),
            "reponse_correcte" => $question->getReponseCorrecte(),
            "reponse_incorrecte_1" => $question->getReponseIncorrecte1(),
            "reponse_incorrecte_2" => $question->getReponseIncorrecte2(),
            "reponse_incorrecte_3" => $question->getReponseIncorrecte3()
        ]);
    }

    public function Delete(Question $question)
    {
        $query = "DELETE FROM questions WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $question->getId()
        ]);
    }
    public function GetByQuizId(int $quiz_id): array
    {
        $stmt = $this->database->getConnection()->prepare("SELECT * FROM questions WHERE quiz_id = ?");
        $stmt->execute([$quiz_id]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $questions;
    }
    public function DeleteByQuiId(int $quizId)
    {
        $stmt = $this->database->getConnection()->prepare("DELETE FROM questions WHERE quiz_id = ?");
        $stmt->execute([$quizId]);
    }
}
