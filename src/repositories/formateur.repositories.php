<?php

require_once "./src/config/database.php";
require_once "./src/models/formateur.php";

class FormateurRepositories{
    private Database $database;

    private function PushArray($stmt, $result) {
         while ($donne = $stmt->fetch()){
            $formateur = new Formateur(
                $donne['nom_prenom'], 
                $donne['email'],
                $donne['telephone'],
                $donne["ville_pays"],
                $donne["linkedin"],
                $donne["intitule_metier"],
                $donne["experience_formation"],
                $donne["detail_experience"],
                $donne["cv"],
                $donne["categories"],
                $donne["titre_cours"],
                $donne["autre_domaine"],
                $donne["objectif"],
                $donne["public_cible"],
                $donne["detail_complementaire"],
                $donne["formats"],
                $donne["format_autre"],
                $donne["duree_estimee"],
                $donne["type_formation"],
                $donne["motivation"],
                $donne["valeur"],
                $donne["prpfil_public"],
                $donne["statut"]
            );
            $formateur->setCreatedAt($donne['created_at']);
            $formateur->setId($donne["id_contenu"]);
            array_push($result, $formateur);
        }
    }


}