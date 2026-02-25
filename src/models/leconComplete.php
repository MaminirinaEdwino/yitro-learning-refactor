<?php

class LeconComplete{
    private int $id;
    private int $utilisateurId;
    private int $leconId;
    private DateTime $dateCompletion;

    public function getId(): int {
        return $this->id;
    }
    public function getUtilisateurId(): int {
        return $this->utilisateurId;
    }
    public function setUtilisateurId(int $utilisateurId) {
        $this->utilisateurId = $utilisateurId;
    }
    public function getLeconId(): int {
        return $this->leconId;
    }
    public function setLeconId(int $leconId) {
        $this->leconId = $leconId;
    }
    public function getDateCompletion(): DateTime {
        return $this->dateCompletion;
    }
    public function setDateCompletion(DateTime $dateCompletion){
        $this->dateCompletion = $dateCompletion;
    }
}