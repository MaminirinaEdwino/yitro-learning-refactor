<?php

class Module {
    private int $id;
    private int $coursId;
    private string $titre;
    private string $details;

    public function getId(): int {
        return $this->id;
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
    public function getDetails(): string {
        return $this->details;
    }
    public function setDetails(string $details) {
        $this->details = $details;
    }
}