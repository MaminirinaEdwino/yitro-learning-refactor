<?php

class Module {
    private int $id;
    private int $coursId;
    private string $titre;
    private string $description;

    public function __construct(
        int $coursId,
        string $titre,
        string $description
    )
    {
        $this->coursId = $coursId;
        $this->titre = $titre;
        $this->description = $description;
    }

    public function getId(): int {
        return $this->id;
    }
    public function setId(int $id){
        $this->id = $id;
    }
    public function getCoursId(): int {
        return $this->coursId;
    }

    public function setCoursId(int $coursId) {
        $this->coursId = $coursId;
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
}