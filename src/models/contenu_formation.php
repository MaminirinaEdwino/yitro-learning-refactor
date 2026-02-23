<?php

class ContenuFormation {
    private int $id_contenu_formation;
    private int $id_formation;
    private string $sous_formation;
    private DateTime $created_at;

    public function getIdContenuFormation(): int {
        return $this->id_contenu_formation;
    }

    public function getIdFormation(): int {
        return $this->id_formation;
    }

    public function setIdFormation(int $idFormation){
        $this->id_formation = $idFormation;
    }
    
}
