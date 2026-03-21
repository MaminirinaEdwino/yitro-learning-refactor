<?php

require_once "./src/config/database.php";
require_once "./src/models/lecons.php";

class LeconRepositories
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
            $var = new Lecons(
                $donne["module_id"],
                $donne["titre"],
                $donne["format"],
                $donne["fichier"]
            );
            $var->setId($donne["id"]);
            array_push($this->result, $var);
        }
    }

    public function Insert(Lecons $lecons)
    {
        $query = "INSERT INTO lecons (module_id, titre, format, fichier) VALUES(:module_id, :titre, :format, :fichier)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "module_id" => $lecons->getModuleId(),
            "titre" => $lecons->getTitre(),
            "format" => $lecons->getFormat(),
            "fichier" => $lecons->getFichier()
        ]);
    }

    public function GetAll(): array
    {
        $result = [];
        $query = "SELECT * FROM lecons";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }
    public function GetById(int $id): Lecons
    {
        $query = "SELECT * FROM lecons WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $id]);
        $this->PushArray($stmt, null);
        return $this->result[0];
    }

    public function Update(Lecons $lecons)
    {
        $query = "UPDATE lecons SET module_id =:module_id, titre=:titre, format=:format, fichier=:fichier WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $lecons->getId(),
            "module_id" => $lecons->getModuleId(),
            "titre" => $lecons->getTitre(),
            "format" => $lecons->getFormat(),
            "fichier" => $lecons->getFichier()
        ]);
    }
    public function Delete(Lecons $lecons)
    {
        $query = "DELETE FROM lecons WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $lecons->getId()
        ]);
    }

    public function GetByModuleId(int $moduleId): array
    {
        $stmt = $this->database->getConnection()->prepare("SELECT * FROM lecons WHERE module_id = ?");
        $stmt->execute([$moduleId]);
        $this->PushArray($stmt, null);
        return $this->result;
    }

    public function DeleteByModuleId(int $moduleId)
    {
        $stmtLecons = $this->database->getConnection()->prepare("DELETE FROM lecons WHERE module_id = ?");
        $stmtLecons->execute([$moduleId]);
    }
}
