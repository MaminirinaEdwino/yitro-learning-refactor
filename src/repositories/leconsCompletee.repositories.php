<?php

require_once "./src/config/database.php";
require_once "./src/models/leconComplete.php";

class LeconsCompletee {
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new LeconComplete(
                $donne["utilisateur_id"],
                $donne["lecon_id"]
            );
            $var->setId($donne["id"]);
            $var->setDateCompletion($donne["date_completion"]);
            array_push($result, $var);
        }
    }

    public function Insert(LeconComplete $leconComplete) {
        $query = "INSERT INTO lecon_completees(utilisateur_id, lecon_id) VALUES(:utilisateur_id, :lecon_id)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "utilisateur_id"=>$leconComplete->getUtilisateurId(),
            "lecon_id"=>$leconComplete->getLeconId()
        ]);
    }

    public function GetAll(): array {
        $result = [];
        $query = "SELECT * FROM lecons_completees";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): LeconComplete {
        $result = [];
        $query = "SELECT * FROM lecons_completees WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$id]);
        $this->PushArray($stmt, $result);
        return $result[0];
    }
    
}