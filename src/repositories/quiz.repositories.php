<?php

require_once "./src/config/database.php";
require_once "./src/models/quiz.php";

class QuizRepositories
{
    private Database $database;
    private array $result;

    public function __construct()
    {
        $this->database = new  Database();
    }
    private function PushArray($stmt, $result)
    {
        $this->result = [];
        while ($donne = $stmt->fetch()) {
            $var = new Quiz(
                $donne["module_id"],
                $donne["titre"],
                $donne["description"],
                $donne["score_minimum"]
            );
            $var->setId($donne["id"]);
            array_push($this->result, $var);
        }
    }

    public function Insert(Quiz $quiz): int
    {
        $query = "INSERT INTO quiz(module_id, titre, description, score_minimum) VALUES(:module_id, :titre, :description, :score_minimum)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "module_id" => $quiz->getModuleId(),
            "titre" => $quiz->getTitre(),
            "description" => $quiz->getDescription(),
            "score_minimum" => $quiz->getScoreMinimum()
        ]);
        return $conn->lastInsertId();
    }

    public function GetAll(): array
    {
        $result = [];
        $query = "SELECT * FROM quiz";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $this->result;
    }

    public function GetTotalQuiz($cours): int
    {
        $stmt = $this->database->getConnection()->prepare("
            SELECT COUNT(*) AS total_quiz
            FROM quiz q
            JOIN modules m ON q.module_id = m.id
            WHERE m.cours_id = ?
        ");
        $stmt->execute([$cours]);
        $total_quiz = $stmt->fetchColumn();
        return $total_quiz;
    }

    public function GetById(int $id): Quiz
    {
        $result = [];
        $query = "SELECT * FROM quiz WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $id]);
        $this->PushArray($stmt, $result);
        return $this->result[0];
    }

    public function Update(Quiz $quiz)
    {
        $query = "UPDATE quiz SET module_id=:module_id, titre=:titre, description=:description, score_minimum=:score_minimum WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "module_id" => $quiz->getModuleId(),
            "titre" => $quiz->getTitre(),
            "description" => $quiz->getDescription(),
            "score_minimum" => $quiz->getScoreMinimum(),
            "id" => $quiz->getId()
        ]);
    }

    public function Delete(Quiz $quiz)
    {
        $query = "DELETE FROM quiz WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $quiz->getId()
        ]);
    }

    public function GetByModuleId(int $moduleId): array
    {
        $stmt = $this->database->getConnection()->prepare("SELECT * FROM quiz WHERE module_id = ?");
        $stmt->execute([$moduleId]);
        $this->PushArray($stmt, null);
        return $this->result;
    }

    public function GetCoursModuleQuizByQuiz(int $quiz_id): array
    {
        $stmt = $this->database->getConnection()->prepare("
    SELECT q.*, m.cours_id, c.titre AS cours_titre, m.titre AS module_titre, c.prix
    FROM quiz q
    JOIN modules m ON q.module_id = m.id
    JOIN cours c ON m.cours_id = c.id
    WHERE q.id = ?
        ");
        $stmt->execute([$quiz_id]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        return $quiz;
    }

    public function GetCoursQuizByFormateurId(int $formateurId): array
    {
        $stmt = $this->database->getConnection()->prepare("
        SELECT q.id, q.titre, q.description, q.score_minimum, c.titre AS cours_titre, m.titre AS module_titre,
            (SELECT COUNT(*) FROM questions WHERE quiz_id = q.id) AS nb_questions
        FROM quiz q
        JOIN modules m ON q.module_id = m.id
        JOIN cours c ON m.cours_id = c.id
        WHERE c.formateur_id = ?
        ");
        $stmt->execute([$formateurId]);
        $quiz = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $quiz;
    }

    public function GetQuizByIdFormateurId(int $quizId, $formateurId): array
    {
        $stmt = $this->database->getConnection()->prepare("
    SELECT q.*, m.cours_id, c.titre AS cours_titre, m.titre AS module_titre
    FROM quiz q
    JOIN modules m ON q.module_id = m.id
    JOIN cours c ON m.cours_id = c.id
    WHERE q.id = ? AND c.formateur_id = ?
");
        $stmt->execute([$quizId, $formateurId]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        return $quiz;
    }
}
