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
}