<?php 

class Inscription {
    private int $id;
    private int $utilisateurId;
    private int $coursId;
    private DateTime $dateInscription;
    private string $statutPayement;

    public function __construct(
        int $utilisateurId,
        int $coursId, 
        string $statutPayement
    )
    {
        $this->utilisateurId = $utilisateurId;
        $this->coursId = $coursId;
        $this->statutPayement = $statutPayement;
    }

    public function getId(): int {
        return $this->id;
    }
    public function setId(int $id) {
        $this->id = $id;
    }
    public function getUtilisateurId(): int {
        return $this->utilisateurId;
    }
    public function setUtilisateur(int $utilisateurId){
        $this->utilisateurId = $utilisateurId;
    }

    public function getCoursId(): int {
        return $this->coursId;
    }

    public function setCoursId(int $coursId){
        $this->coursId = $coursId;
    }

    public function getDateInscription(): DateTime {
        return $this->dateInscription;
    }
    public function setDateInscription(DateTime $dateInscription) {
        $this->dateInscription = $dateInscription;
    }

    public function getStatutPayement(): string {
        return $this->statutPayement;
    }

    public function setStatutPayement(string $statutPayement) {
        $this->statutPayement = $statutPayement;
    }
}