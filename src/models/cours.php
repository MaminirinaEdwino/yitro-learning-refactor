<?php

class Cours {
    private int $id_cours;
    private int $id_formateur;
    private int $id_formation;
    private int $id_contenu_formation;
    private string $titre;
    private float $prix;
    private string $photo;
    private DateTime $created_at;

    public function __construct(
        int $id_formateur,
        int $id_formation,
        int $id_contenu_formation,
        string $titre,
        float $prix,
        string $photo
    )
    {
        $this->id_contenu_formation = $id_contenu_formation;
        $this->id_formateur = $id_formateur;
        $this->id_formation = $id_formation;
        $this->titre = $titre;
        $this->prix = $prix;
        $this->photo = $photo;
    }

    public function getId(): int {
        return $this->id_cours;
    }
    public function setId(int $id){
        $this->id_cours = $id;
    }

    public function getIdFormateur(): int {
        return $this->id_formateur;
    }
    public function setIdFormateur(int $id_formateur){
        $this->id_formateur = $id_formateur;
    }

    public function getIdFormation(): int {
        return $this->id_formation;
    }
    public function setIdFormation(int $id_formation){
        $this->id_formation = $id_formation;
    }

    public function getIdContenuFormation(): int {
        return $this->id_contenu_formation;
    }
    public function setIdContenuFormation(int $id_contenu_formation){
        $this->id_contenu_formation = $id_contenu_formation;
    }

    public function getTitre(): string {
        return $this->titre;
    }
    public function setTitre(string $titre)  {
        $this->titre = $titre;
    }
    

}