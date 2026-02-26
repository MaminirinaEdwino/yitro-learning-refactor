<?php

require_once "./src/models/contenu_formation.php";
require_once "./src/config/database.php";

class ContenueFormationRepositories {
    private Database $database;

    public function Insert(ContenuFormation $contenuFormation){
        $query  = "INSERT INTO contenu_formations (formation_id, sous_formation) VALUES(:formation_id, :sous_formation)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            
        ]);
    }
}