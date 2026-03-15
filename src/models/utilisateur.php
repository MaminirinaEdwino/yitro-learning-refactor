<?php

class Utilisateur{
    private int $id;
    private string $nom;
    private string $email;
    private string $mdp;
    private string $telephone;
    private string $photo;
    private string $pays;
    private string $langue;
    private string $autreLangue;
    private string $objectif;
    private string $typeCours;
    private string $niveauFormation;
    private string $niveauEtude;
    private string $accesInternet;
    private string $appareil;
    private string $accessibilite;
    private bool $rgpd = false;
    private bool $charte = false;
    private string $role = "apprenant"|"admin"|"moderator";
    private bool $actif;
    private DateTime $createdAt;


    public function __construct(
        string $nom,
        string $email,
        string $mdp,
        string $telephone,
        string $photo,
        string $pays,
        string $langue,
        string $objectif,
        string $autreLangue,
        string $typeCours,
        string $niveauFormation,
        string $niveauEtude,
        string $accesInternet,
        string $appareil,
        bool $rgpd,
        bool $charte,
        string $role
    )
    {
        $this->nom = $nom;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->telephone = $telephone;
        $this->photo = $photo;
        $this->pays = $pays;
        $this->langue = $langue;
        $this->autreLangue = $autreLangue;
        $this->objectif = $objectif;
        $this->typeCours = $typeCours;
        $this->niveauEtude = $niveauEtude;
        $this->niveauFormation = $niveauFormation;
        $this->accesInternet = $accesInternet;
        $this->appareil = $appareil;
        $this->rgpd = $rgpd;
        $this->charte = $charte;
        $this->role = $role;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }
    public function setCreatedAt(DateTime $createdAt){
        $this->createdAt = $createdAt;
    }
    public function getId(): int {
        return $this->id;
    }
    public function setId(int $id) {
        $this->id= $id;
    }
    public function getNom(): string {
        return $this->nom;
    }
    public function setNom(string $nom) {
        $this->nom = $nom;
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function setEmail(string $email) {
        $this->email = $email;
    }
    public function getMdp(): string {
        return $this->mdp;
    }
    public function setMdp(string $mdp) {
        $this->mdp= $mdp;
    }
    public function getTelephone(): string {
        return $this->telephone;
    }
    public function setTelephone(string $telephone) {
        $this->telephone = $telephone;
    }
    public function getPhoto(): string {
        return $this->photo;
    }
    public function setPhoto(string $photo) {
        $this->photo = $photo;
    }
    public function getPays(): string {
        return $this->pays;
    }
    public function setPays(string $pays) {
        $this->pays = $pays;
    }
    public function getActif(): bool {
        return $this->actif;
    }
    public function setActif(bool $actif){
        $this->actif = $actif;
    }
    public function getRole(): string {
        return $this->role;
    }
    public function setRole(string $role){
        $this->role = $role;
    }
    public function getCharte(): bool {
        return $this->charte;
    }
    public function setCharte(bool $charte) {
        $this->charte = $charte;
    }
    public function getRgpd(): bool {
        return $this->rgpd;
    }
    public function setRgpd(bool $rgpd){
        $this->rgpd = $rgpd;
    }
    public function getAccessibilite(): string {
        return $this->accessibilite;
    }
    public function setAccessibilite(string $accessibilite) {
        $this->accessibilite = $accessibilite;
    }
    public function getAppareil(): string {
        return $this->appareil;
    }
    public function setAppareil(string $appareil){
        $this->appareil = $appareil;
    }
    public function getAccesInternet(): string {
        return $this->accesInternet;
    }
    public function setAccesInternet(string $accesInternet) {
        $this->accesInternet = $accesInternet;
    }
    public function getNiveauEtude(): string {
        return $this->niveauEtude;
    }
    public function setNiveauEtude(string $niveauEtude){
        $this->niveauEtude = $niveauEtude;
    }
    public function getNiveauFormation(): string {
        return $this->niveauFormation;
    }
    public function setNiveauFormation(string $niveauFormation){
        $this->niveauFormation = $niveauFormation;
    }
    public function setTypeCours(string $typeCours) {
        $this->typeCours = $typeCours;
    }
    public function getTypeCours(): string {
        return $this->typeCours;
    }
    public function getObjectif(): string {
        return $this->objectif;
    }
    public function setObjectif(string $objectif){
        $this->objectif = $objectif;
    }
    public function getLangue(): string {
        return $this->langue;
    }
    public function setLangue(string $langue){
        $this->langue = $langue;
    }
    public function getAutreLangue(): string {
        return $this->autreLangue;
    }
    public function setAutreLangue(string $autreLangue){
        $this->autreLangue = $autreLangue;
    }
}