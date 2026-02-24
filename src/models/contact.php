<?php

class Contact{
    private int $id;
    private string $nom;
    private string $email;
    private string $sujet;
    private string $message;
    private DateTime $created_at;

    public function __construct()
    {
        
    }

    public function getId(): int{
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom) {
        $this->nom = $nom;
    } 

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function getSujet(): string {
        return $this->sujet;
    }

    public function setSujet(string $sujet) {
        $this->sujet = $sujet;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message) {
        $this->message = $message;
    }

    public function getCreatedAt(): DateTime {
        return $this->created_at;
    }


}