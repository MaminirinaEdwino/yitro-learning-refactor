<?php

class Quiz {
    private int $id;
    private int $moduleId;
    private string $titre;
    private string $description;
    private int $scoreMinimum;

    public function getId(): int {
        return $this->id;
    }
    public function getModuleId(): int {
        return $this->moduleId;
    }
    public function setModuleId(int $moduleId) {
        $this->moduleId = $moduleId;
    }
    public function getTitre(): string {
        return $this->titre;
    }
    public function setTitre(string $titre) {
        $this->titre = $titre;
    }

    public function getDescription(): string {
        return $this->description;
    }
    public function setDescription(string $description) {
        $this->description = $description;
    }
    public function getScoreMinimum(): int {
        return $this->scoreMinimum;
    }
    public function setScoreMinimum(int $scoreMinimum){
        $this->scoreMinimum = $scoreMinimum;
    }
}