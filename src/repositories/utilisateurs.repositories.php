<?php

require_once "./src/config/database.php";
require_once "./src/models/utilisateur.php";

class UtilisateursRepositories {
    private Database $database;
    private array $result; 

    public function __construct()
    {
        $this->database = new Database();
    }
    private function PushArray($stmt, $result)
    {
        $this->result = [];
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
            array_push($this->result, $var);
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

    public function GetAll(): array {
        $result = [];
        $query = "SELECT * FROM utilisateurs";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetForAuth(string $email, string $mdp): array {
        $query = "SELECT * FROM utilisateurs WHERE email = :email, mot_de_passe=:mdp";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "email"=>$email,
            "mdp"=>$mdp
        ]);
        $this->PushArray($stmt, null);
        return $this->result;
    }

    public function GetById(int $id): Quiz {
        $result = [];
        $query = "SELECT * FROM utilisateurs WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$id]);
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function Update(Utilisateur $utilisateur)
    {
        $query = "UPDATE utilisateurs SET  
        nom = :nom, 
        email = :email,
        mot_de_passe = :mot_de_passe,
        telephone = :telephone, 
        photo = :photo,
        pays = :pays,
        langue = :langue,
        objectifs = :objectifs,
        autre_langues = :autre_langue,
        type_cours = :type_cours,
        niveau_formation = :niveau_formation,
        niveau_etude = :nivea_etude,
        acces_internet = :acces_internet,
        appareil = :appareil,
        accessibilite = :accessibilite,
        rgpd = :rgpd,
        charte :charte,
        role = :role
        WHERE id=:id";
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
        "role"=>$utilisateur->getRole(),
        "id"=>$utilisateur->getId() 
        ]);
    }

    public function Delete(Utilisateur $utilisateur)
    {
        $query = "DELETE FROM utilisateurs WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $utilisateur->getId()
        ]);
    }
}