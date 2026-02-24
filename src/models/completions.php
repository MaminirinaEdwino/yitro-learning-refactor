<?php

class completions {
    private int $id;
    private int $utilisateur_id;
    private int $module_id;
    private int $cours_id;
    private DateTime $date_completion;

    public function getId(): int {
        return $this->id;
    }

    public function getUtilisateurId(): int {
        return $this->utilisateur_id;
    }

    public function setUtilisateurId(int $utilisateur_id){
        $this->utilisateur_id = $utilisateur_id;
    }

    public function getModuleId(): int {
        return $this->module_id;
    }

    public function setModuleId(int $module_id) {
        $this->module_id = $module_id;
    }

    public function getCoursId(): int {
        return $this->cours_id;
    }

    public function setCoursId(int $coursId){
        $this->cours_id = $coursId;
    }

    public function getDateCompletion(): DateTime {
        return $this->date_completion;
    }

    public function setDateCompletion(DateTime $date_completion) {
        $this->date_completion = $date_completion;
    }
}