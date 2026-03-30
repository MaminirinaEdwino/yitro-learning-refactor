<?php

require_once "./src/models/completions.php";
require_once "./src/config/database.php";

class CompletionsRepositories
{
    private Database $database;
    private array $result;

    private function PushResult($stmt, $result)
    {
        $this->result = [];
        while ($donne = $stmt->fetch()) {
            $completion = new Completions($donne['utilisateur_id'], $donne['module_id'], $donne['cours_id']);
            $completion->setDateCompletion($donne['date_completion']);
            $completion->setId($donne["id"]);
            array_push($this->result, $completion);
        }
    }
    public function __construct()
    {
        $this->database = new Database;
    }
    public function Insert(Completions $completions)
    {
        $query = "INSERT INTO completions (utilisateur_id, module_id, cours_id) VALUES(:utilisateur_id, :module_id, :cours_id)";
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "utilisateur_id" => $completions->getUtilisateurId(),
            "module_id" => $completions->getModuleId(),
            "cours_id" => $completions->getCoursId()
        ));
    }

    public function DeleteByIdCoursUd($utilisateur_id, $module_id)
    {
        $stmt = $this->database->getConnection()->prepare("DELETE FROM completions WHERE utilisateur_id = ? AND module_id = ?");
        $stmt->execute([$utilisateur_id, $module_id]);
    }
    
    public function GetAll(): array
    {
        $result = [];
        $query = "SELECT * FROM completions";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushResult($stmt, $result);
        return $result;
    }

    public function GetByUtilisateur(int $utilisateurId): array
    {
        $query = "SELECT * FROM completions WHERE utilisateur_id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "id" => $utilisateurId
        ));
        $result = [];
        $this->PushResult($stmt, $result);
        return $result;
    }
    public function GetByModuleId(int $module_id): array
    {
        $query = "SELECT * FROM completions WHERE module_id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "id" => $module_id
        ));
        $result = [];
        $this->PushResult($stmt, $result);
        return $result;
    }
    public function GetByCoursId(int $cours_id): array
    {
        $query = "SELECT * FROM completions WHERE cours_id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "id" => $cours_id
        ));
        $result = [];
        $this->PushResult($stmt, $result);
        return $result;
    }
    public function GetById(int $id): array
    {
        $query = "SELECT * FROM completions WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "id" => $id
        ));
        $result = [];
        $this->PushResult($stmt, $result);
        return $result;
    }

    public function GetByCoursUserId(int $cours_id, $utilisateur_id): array
    {
        $stmt = $this->database->getConnection()->prepare("SELECT module_id FROM completions WHERE utilisateur_id = ? AND cours_id = ?");
        $stmt->execute([$utilisateur_id, $cours_id]);
        $completed_modules = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $completed_modules;
    }

    public function DeleteByCoursId(int $id)
    {
        $stmtCompletions = $this->database->getConnection()->prepare("
    DELETE completions FROM completions 
    INNER JOIN modules ON completions.module_id = modules.id 
    WHERE modules.cours_id = ?
");
        $stmtCompletions->execute([$id]);
    }

    public function DeleteByModueId(int $module_id)
    {
        $stmtCompletions = $this->database->getConnection()->prepare("DELETE FROM completions WHERE module_id = ?");
        $stmtCompletions->execute([$module_id]);
    }

    public function GetCompleteModule(int $apprenant, int $cours): int
    {
        $stmt = $this->database->getConnection()->prepare("
            SELECT COUNT(*) AS modules_completes
            FROM completions
            WHERE utilisateur_id = ? AND cours_id = ?
        ");
        $stmt->execute([$apprenant, $cours]);
        $modules_completes = $stmt->fetchColumn();
        return $modules_completes;
    }
}
