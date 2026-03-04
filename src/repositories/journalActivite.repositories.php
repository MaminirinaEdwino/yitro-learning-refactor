<?php

require_once "./src/config/database.php";
require_once "./src/models/journalActivite.php";

class JournalActiviteRepositories {
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new JournalActivite(
                $donne["admin_id"],
                $donne["action"],
                $donne["details"]
            );
            $var->setCreatedAt($donne['created_at']);
            $var->setId($donne["id"]);
            array_push($result, $var);
        }
    }

    public function Insert(JournalActivite $journalActivite) {
        $query = "INSERT INTO journal_activite(admin_id, action, details) VALUES(:admin_id, :action, :details)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "admin_id"=>$journalActivite->getAdminId(),
            "action"=>$journalActivite->getAction(),
            "details"=>$journalActivite->getDetails()
        ]);
    }

    public function GetAll(): array{
        $query = "SELECT * FROM journal_activite";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = [];
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): JournalActivite {
        $query = "SELECT * FROM journal_activite WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$id]);
        $result = [];
        $this->PushArray($stmt, $result);
        return $result[0];
    }
}