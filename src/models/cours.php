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

    
    
    
}