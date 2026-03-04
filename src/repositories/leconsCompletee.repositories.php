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

    
}