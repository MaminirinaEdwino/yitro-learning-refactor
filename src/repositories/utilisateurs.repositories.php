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

    public function Insert(Utilisateur $utilisateur) {
        $query = "INSERT INTO utilisateurs(
        nom, 
        email,
        mot_de_passe,
        telephone, 
        photo,
        pays,
        langue,
        objectifs,
        autre_langues,
        type_cours,
        niveau_formation,
        niveau_etude,
        acces_internet,
        appareil,
        accessibilite,
        rgpd,
        charte,
        role ) VALUES(
        :nom, 
        :email,
        :mot_de_passe,
        :telephone, 
        :photo,
        :pays,
        :langue,
        :objectifs,
        :autre_langues,
        :type_cours,
        :niveau_formation,
        :niveau_etude,
        :acces_internet,
        :appareil,
        :accessibilite,
        :rgpd,
        :charte,
        :role
        )";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
        "nom"=>$utilisateur->getNom(), 
        "email"=>$utilisateur->getEmail(),
        "mot_de_passe"=>$utilisateur->getMdp(),
        "telephone"=>$utilisateur->getTelephone(), 
        "photo"=>$utilisateur->getPhoto(),
        "pays"=>$utilisateur->getPays(),
        "langue"=>$utilisateur->getLangue(),
        "objectifs"=>$utilisateur->getObjectif(),
        "autre_langues"=>$utilisateur->getAutreLangue(),
        "type_cours"=>$utilisateur->getTypeCours(),
        "niveau_formation"=>$utilisateur->getNiveauFormation(),
        "niveau_etude"=>$utilisateur->getNiveauEtude(),
        "acces_internet"=>$utilisateur->getAccesInternet(),
        "appareil"=>$utilisateur->getAppareil(),
        "accessibilite"=>$utilisateur->getAccessibilite(),
        "rgpd"=>$utilisateur->getRgpd(),
        "charte"=>$utilisateur->getCharte(),
        "role"=>$utilisateur->getRole() 
        ]);
    }

}