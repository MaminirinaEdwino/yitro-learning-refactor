<?php

require_once "./src/config/database.php";
require_once "./src/models/journalActivite.php";

class JournalActiviteRepositories {
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new JournalActivite(
                $donne["admin_id"],
                $donne["action"],
                $donne["details"]
            );
            $var->setCreatedAt($donne['created_at']);
            $var->setId($donne["id"]);
            array_push($result, $var);
        }
    }
}