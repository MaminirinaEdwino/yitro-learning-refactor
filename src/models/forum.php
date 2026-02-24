<?php

class Forum {
    private int $id;
    private int $courId;
    private string $titre;
    private string $description;
    private DateTime $dateCreation;

    public function getId(): int {
        return $this->id;
    }

    public function getCoursId(): int {
        return $this->courId;
    }

    public function setCoursId(int $coursId) {
        $this->courId = $coursId;
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

    public function getDateCreation(): DateTime {
        return $this->dateCreation;
    }

    public function setDateCreation(DateTime $dateCreation) {
        $this->dateCreation = $dateCreation;
    }
}