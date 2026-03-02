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
            "autre_domainte"=>$formateur->getAutreDomain(),
            "titre_cours"=>$formateur->getTitreCours(),
            "objectif"=>$formateur->getObjectif(),
            "public_cible"=>$formateur->getPublicCible(),
            "detailcomplementaire"=>$formateur->getDetailComplementaire(),
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


}