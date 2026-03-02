<?php

require_once "./src/config/database.php";
require_once "./src/models/formation.php";

class FormationRepositories{
    private Database $database;

    private function PushArray($stmt, $result) {
         while ($donne = $stmt->fetch()){
            $formateur = new Formation(
                $donne["nom_formation"]
            );
            $formateur->setCreated_at($donne['created_at']);
            $formateur->setIdFormation($donne["id_formation"]);
            array_push($result, $formateur);
        }
    }
}