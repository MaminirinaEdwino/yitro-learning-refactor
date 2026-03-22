<?php

require_once "./src/config/database.php";
require_once "./src/models/journalActivite.php";

class JournalActiviteRepositories
{
    private Database $database;

    public function __construct()
    {
        $this->database =  new Database();
    }


    private function PushArray($stmt, &$result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new JournalActivite(
                $donne["admin_id"],
                $donne["action"],
                $donne["details"]
            );
            $var->setCreatedAt(new DateTime($donne['created_at']));
            $var->setId($donne["id"]);
            array_push($result, $var);
        }
    }

    public function GetFilterLog($search, $sort, $order): array
    {
        $where = $search ? "WHERE j.action LIKE ? OR j.details LIKE ? OR u.nom LIKE ? OR j.created_at LIKE ?" : "";
        $sql = "SELECT j.*, u.nom FROM journal_activite j JOIN utilisateurs u ON j.admin_id = u.id $where ORDER BY " . ($sort == 'nom' ? 'u.nom' : 'j.' . $sort) . " $order";
        $stmt = $this->database->getConnection()->prepare($sql);
        if ($search) {
            $searchTerm = "%$search%";
            $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        } else {
            $stmt->execute();
        }
        $activites = $stmt->fetchAll();
        return $activites;
    }

    public function MarkRead()
    {
        $stmt = $this->database->getConnection()->prepare("INSERT INTO journal_activite (admin_id, action, details) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], 'Marquer notifications comme lues', 'Notifications des dernières 24 heures']);
    }

    public function DeactivateUser(int $user_id)
    {
        $stmt = $this->database->getConnection()->prepare("INSERT INTO journal_activite (admin_id, action, details) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], 'Désactivation de compte', "Utilisateur ID: $user_id"]);
    }

    public function CountLog(): int
    {
        $stmt = $this->database->getConnection()->query("SELECT COUNT(*) as count FROM journal_activite WHERE DATE(created_at) = CURDATE()");
        $activites_aujourdhui = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        return $activites_aujourdhui;
    }

    public function GetLastLog(): array
    {
        $stmt = $this->database->getConnection()->prepare("SELECT j.*, u.nom FROM journal_activite j JOIN utilisateurs u ON j.admin_id = u.id ORDER BY j.created_at DESC LIMIT 5");
        $stmt->execute();
        $dernieres_activites = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dernieres_activites;
    }

    public function Insert(JournalActivite $journalActivite)
    {
        $query = "INSERT INTO journal_activite(admin_id, action, details) VALUES(:admin_id, :action, :details)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "admin_id" => $journalActivite->getAdminId(),
            "action" => $journalActivite->getAction(),
            "details" => $journalActivite->getDetails()
        ]);
    }

    public function GetAll(): array
    {
        $query = "SELECT * FROM journal_activite";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = [];
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): JournalActivite
    {
        $query = "SELECT * FROM journal_activite WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $id]);
        $result = [];
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function Update(JournalActivite $journalActivite)
    {
        $query = "UPDATE journal_activite SET admin_id = :admin_id, action=:action, details=:details WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "admin_id" => $journalActivite->getAdminId(),
            "action" => $journalActivite->getAction(),
            "details" => $journalActivite->getDetails(),
            "id" => $journalActivite->getId()
        ]);
    }

    public function Delete(JournalActivite $journalActivite)
    {
        $query = "DELETE FROM journal_activite WHERE id =:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $journalActivite->getId()
        ]);
    }
}
