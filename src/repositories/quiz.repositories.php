<?php

require_once "./src/config/database.php";
require_once "./src/models/quiz.php";

class QuizRepositories{
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new Quiz(
                $donne["quiz_id"],
                $donne["texte"],
                $donne["reponse_correcte"],
                $donne["reponse_incorrecte_1"],
                $donne["reponse_incorrecte_2"],
                $donne["reponse_incorrecte_3"]
            );
            $var->setId($donne["id"]);
            array_push($result, $var);
        }
    }
}