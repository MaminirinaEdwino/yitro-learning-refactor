<?php

require_once "./src/config/database.php";
require_once "./src/models/module.php";

class ModuleRepositories {
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new Module(
                $donne["cours_id"],
                $donne["titre"],
                $donne["description"]
            );
            $var->setId($donne["id"]);
            array_push($result, $var);
        }
    }
}