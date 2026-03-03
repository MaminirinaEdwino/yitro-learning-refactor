<?php

require_once "./src/config/database.php";
require_once "./src/models/forum.php";


class ForumRepositories{
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $forum = new Forum(
                $donne["nom_formation"]
            );
            $forum->setDateCreation($donne['date_creation']);
            $forum->setId($donne["id"]);
            array_push($result, $forum);
        }
    }
}