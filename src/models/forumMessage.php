<?php

class ForumMessage{
    private int $id;
    private int $coursId;
    private int $utilisateurId;
    private string $message;
    private DateTime $date;
    private bool $lu;

    public function __construct()
    {
        
    }

    public function getId(): int{
        return $this->id;
    }

    public function getCoursId(): int {
        return $this->coursId;
    }

    public function setCoursId(int $coursId) {
        $this->coursId = $coursId;
    }
    
    public function getUtilisateurId(): int {
        return $this->utilisateurId;
    }
    public function setUtilisateurId(int $utilisateurId){
        $this->utilisateurId = $utilisateurId;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message){
        $this->message = $message;
    }

    public function getDate(): DateTime{
        return $this->date;
    }

    public function setDate(DateTime $date){
        $this->date = $date;
    }
    
    public function getLu(): bool {
        return $this->lu;
    }

    public function setLu(bool $lu) {
        $this->lu = $lu;
    }
}