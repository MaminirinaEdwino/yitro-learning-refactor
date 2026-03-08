<?php

require_once "./src/config/database.php";
require_once "./src/models/resultatQuiz.php";

class ResultatQuizRepositories {
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new ResultatQuiz(
                $donne["utilisateur_id"],
                $donne["quiz_id"],
                $donne["score"]
            );
            $var->setId($donne["id"]);
            $var->setDate($donne["date"]);
            array_push($result, $var);
        }
    }
}