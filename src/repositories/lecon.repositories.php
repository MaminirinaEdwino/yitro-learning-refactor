<?php

require_once "./src/config/database.php";
require_once "./src/models/lecons.php";

class LeconRepositories {
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new Lecons(
                $donne["module_id"],
                $donne["titre"],
                $donne["format"],
                $donne["fichier"]
            );
            $var->setId($donne["id"]);
            array_push($result, $var);
        }
    }

    
}