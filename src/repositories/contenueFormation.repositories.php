<?php

require_once "./src/models/contenu_formation.php";
require_once "./src/config/database.php";

class ContenueFormationRepositories {
    private Database $database;
    private function PushArray($stmt, $result) {
         while ($donne = $stmt->fetch()){
            $completion = new ContenuFormation($donne['formation_id'], $donne['sous_formation']);
            $completion->setCreatedAt($donne['created_at']);
            $completion->setIdContenuFormation($donne["id_contenu"]);
            array_push($result, $completion);
        }
    }

    public function Insert(ContenuFormation $contenuFormation){
        $query  = "INSERT INTO contenu_formations (formation_id, sous_formation) VALUES(:formation_id, :sous_formation)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "formation_id"=>$contenuFormation->getIdFormation(),
            "sous_formation"=>$contenuFormation->getSousFormation()
        ]);
    }
    public function GetAll(): array{
        $result = [];
        $query = "SELECT * FROM contenu_formations";
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

}