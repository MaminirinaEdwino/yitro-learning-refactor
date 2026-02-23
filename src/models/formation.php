<?php
class Formation{
    private int $id_formation; 
    private string $nom_formation;
    private DateTime $created_at;

    public function getId_formation(): int {
        return $this->id_formation;
    }

    public function getNom_formation(): string {
        return $this->nom_formation;
    }

    public function setNom_formation(string $nom_formation){
        $this->nom_formation = $nom_formation;
    }

    public function getCreated_at(): DateTime{
        return $this->created_at;
    }
}