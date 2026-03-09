<?php

require_once "./src/config/database.php";
require_once "./src/models/utilisateur.php";

class UtilisateursRepositories {
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new Utilisateur(
                $donne["nom"],
                $donne["email"],
                $donne["mot_de passe"],
                $donne["telephone"],
                $donne["photo"],
                $donne["pays"],
                $donne["langue"],
                $donne["objectifs"],
                $donne["autre_langue"],
                $donne("type_cours"),
                $donne["niveau_formation"],
                $donne["niveau_etude"],
                $donne["acces_internet"],
                $donne["appareil"],
                $donne["accessibilite"],
                $donne["rgpd"],
                $donne["charte"],
                $donne["role"]
            );
            $var->setId($donne["id"]);
            $var->setCreatedAt($donne["date"]);
            array_push($result, $var);
        }
    }

}