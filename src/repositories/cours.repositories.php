<?php

require_once "./src/config/database.php";
require_once "./src/models/cours.php";

class CoursRepositories {
    private Database $database;

    private function PushArray($stmt, $result) {
         while ($donne = $stmt->fetch()){
            $completion = new ContenuFormation($donne['formation_id'], $donne['sous_formation']);
            $completion->setCreatedAt($donne['created_at']);
            $completion->setIdContenuFormation($donne["id_contenu"]);
            array_push($result, $completion);
        }
    }
}