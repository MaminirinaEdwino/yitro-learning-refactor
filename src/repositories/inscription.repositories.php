<?php

require_once "./src/config/database.php";
require_once "./src/models/inscription.php";

class InscriptionRepositories {
    private Database $database;
    
    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $forum = new Inscription(
                $donne["utilisateur_id"],
                $donne["cours_id"],
                $donne["statut_payement"]
            );
            $forum->setDateInscription($donne['date_inscription']);
            $forum->setId($donne["id"]);
            array_push($result, $forum);
        }
    }

    public function Insert(Inscription $inscription) {
        $query = "INSERT INTO inscription (utilisateur_id, cours_id) VALUES(:utilisateur_id, :cours_id)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "utilisateur_id"=>$inscription->getUtilisateurId(),
            "cours_id"=>$inscription->getCoursId()
        ]);
    }

    
}