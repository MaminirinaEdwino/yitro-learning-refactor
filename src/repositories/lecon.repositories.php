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

    public function Insert(Lecons $lecons) {
        $query = "INSERT INTO lecons (module_id, titre, format, fichier) VALUES(:module_id, :titre, :format, :fichier)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "module_id"=>$lecons->getModuleId(),
            "titre"=>$lecons->getTitre(),
            "format"=>$lecons->getFormat(),
            "fichier"=>$lecons->getFichier()
        ]);
    }

    
}