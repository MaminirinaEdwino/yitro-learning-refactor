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
    private ?DateTime $created_at ;
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
    public function setIntituleMetier(string $metier){
        $this->intitule_metier = $metier;
    }
    public function getExperienceFormation(): string {
        return $this->experience_formation;
    }
    public function setExperienceFormation(string $experience_formation) {
        $this->experience_formation = $experience_formation;
    }

    public function getDetailExperience(): string{
        return $this->detail_experience;
    }

    public function setDetailExperience(string $detail_experience){
        $this->detail_experience = $detail_experience;
    }

    public function getCv(): string{
        return $this->cv;
    }

    public function setCv(string $cv){
        $this->cv = $cv;
    }

    public function getAutreDomain(): string{
        return $this->autre_domaine;
    }

    public function setAutreDomain(string $autre_domaine){
        $this->autre_domaine = $autre_domaine;
    }

    public function getTitreCours(): string{
        return $this->titre_cours;
    }

    public function setTitreCours(string $titre_cours){
        $this->titre_cours = $titre_cours;
    }

    public function getObjectif(): string{
        return $this->objectif;
    }

    public function setObjectif(string $objectif){
        $this->objectif = $objectif;
    }

    public function getDetailComplementaire(): string{
        return $this->detail_complementaire;
    }

    public function setDetailsComplementaire(string $detail_complementaire){
        $this->detail_complementaire = $detail_complementaire;
    }

    public function getFormats():string{
        return $this->formats;
    }

    public function setFormats(string $formats){
        $this->formats = $formats;
    }

    public function getFormatAutres(): string{
        return $this->format_autre;
    }

    public function setFormatAutres(string $format_autre){
        $this->format_autre = $format_autre;
    }

    public function getDureeEstime(): string{
        return $this->duree_estimee;
    }

    public function setDureeEstime(string $duree_estimee) {
        $this->duree_estimee = $duree_estimee;
    }

    public function getTypeFormation(): string{
        return $this->type_formation;
    }

    public function setTypeFormation(string $type_formation){
        $this->type_formation = $type_formation;
    }

    public function getMotivation(): string{
        return $this->motivation;
    }

    public function setMotivation(string $motivation){
        $this->motivation = $motivation;
    }

    public function getValeur(): string{
        return $this->valeur;
    }

    public function setValeur(string $valeur){
        $this->valeur = $valeur;
    }

    public function getProfilPublic(): string{
        return $this->profil_public;
    }

    public function setProfilPublic(string $profil_public){
        $this->profil_public = $profil_public;
    }

    public function getStatut(): string{
        return $this->statut;
    }

    public function setStatut(string $statut){
        if (in_array($statut, ['en attente','verifie', 'premium', 'partenaire'])) {
            $this->statut = $statut;
        }
    }

    public function getCreatedAt(): DateTime {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $created_at){
        $this->created_at = $created_at;
    }

    public function getCodeEntree(): string{
        return $this->code_entree;
    }

    public function setCodeEntree(?string $code_entree){
        $this->code_entree = $code_entree;
    }

    public function getPassword(): string{
        return $this->password;
    }

    public function setPassword(string $password){
        $this->password = $password;
    }
}