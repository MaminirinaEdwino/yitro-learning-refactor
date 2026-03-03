<?php
class ContenuFormation {
    private int $id_contenu_formation;
    private int $id_formation;
    private string $sous_formation;
    private DateTime $created_at;

    public function __construct(
        int $id_formation,
        string $sous_formation
    ){
        $this->id_formation = $id_formation;
        $this->sous_formation = $sous_formation;   
    }

    public function setIdContenuFormation(int $id){
        $this->id_contenu_formation = $id;
    }
    
    public function getIdContenuFormation(): int {
        return $this->id_contenu_formation;
    }

    public function getIdFormation(): int {
        return $this->id_formation;
    }

    public function setIdFormation(int $idFormation){
        $this->id_formation = $idFormation;
    }

    public function getSousFormation(): string {
        return $this->sous_formation;
    }

    public function setSousFormation(string $sous_formation){
        $this->sous_formation = $sous_formation;
    }

    public function getCreatedAt(): DateTime {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $created_at){
        $this->created_at = $created_at;
    }
}
