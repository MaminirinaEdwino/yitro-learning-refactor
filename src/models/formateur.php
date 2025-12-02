<?php
class Formateur{
    private int $id;
    private string $nom_prenom;
    private string $email;
    private string $telephone;
    private string $ville_pays;
    private string $linkedin;
    private string $intitule_metier;
    private string $experience_formation;
    private string $detail_experience;
    private string $cv;
    private string $autre_domaine;
    private string $titre_cours;
    private string $objectif;
    private string $detail_complementaire;
    private string $formats;
    private string $format_autre;
    private string $duree_estimee;
    private string $type_formation;
    private string $motivation;
    private string $valeur;
    private string $profil_public;
    private string $statut = 'en attente'|'verifie'|'premium'|'partenaire';
    private ?datetime $created_at ;
    private ?string $code_entree = null;
    private ?string $password = null; 

    public function getId(): int{
        return $this->id;
    }

    public function getNomPrenom(): string{
        return $this->nom_prenom;
    }
    public function setNomPrenom(string $nom_prenom){
        $this->nom_prenom = $nom_prenom;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function setEmail(string $email){
        $this->email = $email;
    }

    public function getVillePays(): string{
        return $this->ville_pays;
    }

    public function setVillePays(string $ville_pays){
        $this->ville_pays =  $ville_pays;
    }

    public function getTelephone(): string{
        return $this->telephone;
    }

    public function setTelephone(string $telephone){
        $this->telephone = $telephone;
    }

    public function getLinkedin(): string{
        return $this->linkedin;
    }

    public function setLinkedin(string $linkedin){
        $this->linkedin = $linkedin;
    }
    public function getIntituleMetier(): string{
        return $this->intitule_metier;
    }
}