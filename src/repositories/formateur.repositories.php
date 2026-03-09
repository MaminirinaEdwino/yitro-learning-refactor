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

    public function Insert(Formateur $formateur) {
        $query = "INSERT INTO formateurs(nom_prenom, email, telephone,ville_pays, linkedin, intitule_metier, experience_formation, detail_experience, cv, categories, autre_domaine, titre_cours, objectif, public_cible, detail_complementaire, formats, format_autres, duree_estimee, type_formation, motivation, valeurs, profil_public, statut) VALUES (:nom_prenom, :email, :telephone, :ville_pays, :linkedin, :intitule_metier, :experience_formation, :detail_experience, :cv, :categories, :autre_domaine, :titre_cours, :objectif, :public, :detail_complementaire, :formats, :format_autres, :duree_estimee, :type_formation, :motivation, :valeurs, :profil_public, :statut)";


        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "nom_prenom"=>$formateur->getNomPrenom(),
            "email"=>$formateur->getEmail(),
            "telephone"=>$formateur->getTelephone(),
            "linkedin"=>$formateur->getLinkedin(),
            "ville_pays"=>$formateur->getVillePays(),
            "intitule_metier"=>$formateur->getIntituleMetier(),
            "experience_formation"=>$formateur->getExperienceFormation(),
            "detail_experience"=>$formateur->getDetailExperience(),
            "cv"=>$formateur->getCv(),
            "categories"=>$formateur->getCategories(),
            "autre_domaine"=>$formateur->getAutreDomain(),
            "titre_cours"=>$formateur->getTitreCours(),
            "objectif"=>$formateur->getObjectif(),
            "public_cible"=>$formateur->getPublicCible(),
            "detail_complementaire"=>$formateur->getDetailComplementaire(),
            "formats"=>$formateur->getFormats(),
            "format_autre"=>$formateur->getFormatAutres(),
            "duree_estimee"=>$formateur->getDureeEstime(),
            "type_formation"=>$formateur->getTypeFormation(),
            "motivation">$formateur->getMotivation(),
            "valeurs"=>$formateur->getValeur(),
            "profil_public"=>$formateur->getProfilPublic(),
            "statut"=>$formateur->getStatut()
        ]);
    }

    public function GetAll(): array {
        $result = [];
        $query = "SELECT * FROM formateurs";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Formateur {
        $result = [];
        $query = "SELECT * FROM formateurs WHERE id =:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$id]);
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function Delete(Formateur $formateur) {
        $query = "DELETE FROM formateurs WHERE id =:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$formateur->getId()]);
    }

    public function Update(Formateur $formateur) {
        $query = "UPDATE formateurs SET nom_prenom =:nom_prenom, email =:email, telephone =:telephone, ville_pays =:ville_pays, linkedin =:linkedin, intitule_metier =:intitule_metier, experience_formation =:experience_formation, detail_experience =:detail_experience, cv =:cv, categories=:categories, autre_domaine =:autre_domaine, titre_cours=:titre_cours, objectif =:objectif, public_cible =:public_cible, detail_complementaire =:detail_complementaire, formats =:formats, format_autre =:format_autre, duree_estimee =:duree_estimee, type_formation =:type_formation, motivation =:motivation, valeurs =:valeurs, profil_public=:profil_public, statut=:statut WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "nom_prenom"=>$formateur->getNomPrenom(),
            "email"=>$formateur->getEmail(),
            "telephone"=>$formateur->getTelephone(),
            "linkedin"=>$formateur->getLinkedin(),
            "ville_pays"=>$formateur->getVillePays(),
            "intitule_metier"=>$formateur->getIntituleMetier(),
            "experience_formation"=>$formateur->getExperienceFormation(),
            "detail_experience"=>$formateur->getDetailExperience(),
            "cv"=>$formateur->getCv(),
            "categories"=>$formateur->getCategories(),
            "autre_domaine"=>$formateur->getAutreDomain(),
            "titre_cours"=>$formateur->getTitreCours(),
            "objectif"=>$formateur->getObjectif(),
            "public_cible"=>$formateur->getPublicCible(),
            "detail_complementaire"=>$formateur->getDetailComplementaire(),
            "formats"=>$formateur->getFormats(),
            "format_autre"=>$formateur->getFormatAutres(),
            "duree_estimee"=>$formateur->getDureeEstime(),
            "type_formation"=>$formateur->getTypeFormation(),
            "motivation">$formateur->getMotivation(),
            "valeurs"=>$formateur->getValeur(),
            "profil_public"=>$formateur->getProfilPublic(),
            "statut"=>$formateur->getStatut(),
            "id"=>$formateur->getId()
        ]);
    }
}