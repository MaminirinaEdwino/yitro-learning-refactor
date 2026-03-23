<?php

require_once "./src/config/database.php";
require_once "./src/models/module.php";

class ModuleRepositories
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
            $var = new Module(
                $donne["cours_id"],
                $donne["titre"],
                $donne["description"]
            );
            $var->setId($donne["id"]);
            array_push($this->result, $var);
        }
    }

    public function Insert(Module $module): int
    {
        $query = "INSERT INTO modules(cours_id, titre, description) VALUES(:cours_id, :titre, :description)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "cours_id" => $module->getCoursId(),
            "titre" => $module->getTitre(),
            "description" => $module->getDescription()
        ]);
        return $conn->lastInsertId();
    }

    public function GetAll(): array
    {
        $result = [];
        $query = "SELECT * FROM modules";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Module
    {
        $query = "SELECT * FROM modules WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $id]);
        $this->PushArray($stmt, null);
        return $this->result[0];
    }

    public function Update(Module $module)
    {
        $query = "UPDATE modules SET cours_id = :cours_id, titre=:titre, description =:description WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "cours_id" => $module->getCoursId(),
            "titre" => $module->getTitre(),
            "description" => $module->getDescription(),
            "id" => $module->getId()
        ]);
    }

    public function Delete(Module $module)
    {
        $query = "DELETE FROM modules WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $module->getId()
        ]);
    }

    public function GetByCoursId(int $cours_id): array
    {
        $stmt = $this->database->getConnection()->prepare("SELECT * FROM modules WHERE cours_id = ?");
        $stmt->execute([$cours_id]);
        $this->PushArray($stmt, null);
        return $this->result;
    }
    public function GetByCoursId2(int $cours_id): array
    {
        $stmt = $this->database->getConnection()->prepare("SELECT * FROM modules WHERE cours_id = ?");
        $stmt->execute([$cours_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetByCoursIdArray(int $cours_id): array
    {
        $stmt = $this->database->getConnection()->prepare("SELECT * FROM modules WHERE cours_id = ?");
        $stmt->execute([$cours_id]);
        $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $modules;
    }

    public function GetModuleByCours(int $cours_id): array
    {
        $stmt = $this->database->getConnection()->prepare("SELECT id FROM modules WHERE cours_id = ?");
        $stmt->execute([$cours_id]);
        $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $modules;
    }

    public function GetTotalModule(int $id): int
    {
        $stmt = $this->database->getConnection()->prepare("SELECT COUNT(*) AS total_modules FROM modules WHERE cours_id = ?");
        $stmt->execute([$id]);
        $total_modules = $stmt->fetchColumn();
        return $total_modules;
    }

    public function GetModuletermine(int $userId, int $coursId)
    {
        $stmt = $this->database->getConnection()->prepare("
            SELECT m.titre
            FROM completions c
            JOIN modules m ON c.module_id = m.id
            WHERE c.utilisateur_id = ? AND c.cours_id = ?
        ");
        $stmt->execute([$userId, $coursId]);
        $modules_termines = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $modules_termines;
    }

    public function GetLastInsertId(): int
    {
        return $this->database->getConnection()->lastInsertId();
    }

    public function DeleteByCoursId(int $id)
    {
        $stmtModules = $this->database->getConnection()->prepare("DELETE FROM modules WHERE cours_id = ?");
        $stmtModules->execute([$id]);
    }
}
