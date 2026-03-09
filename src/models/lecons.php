<?php

class Lecons{
    private int $id;
    private int $moduleId;
    private string $titre;
    private string $format;
    private string $fichier;

    public function __construct(
        int $moduleId,
        string $titre,
        string $format,
        string $fichier
    )
    {
        $this->moduleId = $moduleId;
        $this->titre = $titre;
        $this->format = $format;
        $this->fichier = $fichier;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function setTitre(string $titre){
        $this->titre = $titre;
    }
    public function getModuleId(): int {
        return $this->moduleId;
    }
    public function setModuleId(int $moduleId) {
        $this->moduleId = $moduleId;
    }

    public function getFormat(): string {
        return $this->format;
    }
    public function setFormat(string $format) {
        $this->format = $format;
    }

    public function getFichier(): string {
        return $this->fichier;
    }
    public function setFichier(string $fichier) {
        $this->fichier = $fichier;
    }
}