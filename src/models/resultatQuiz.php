<?php

class ResultatQuiz {
    private int $id;
    private int $utilisateurId;
    private int $quizId;
    private int $score;
    private DateTime $date;

    public function __construct(
        int $utilisateurId,
        int $quizId,
        int $score
    )
    {
        $this->utilisateurId = $utilisateurId;
        $this->quizId = $quizId;
        $this->score = $score;
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
    public function setUtilisateurId(int $utilisateurId) {
        $this->utilisateurId = $utilisateurId;
    }
    
    public function getQuizId(): int {
        return $this->quizId;
    }

    public function setQuizId(int $quizId) {
        $this->quizId = $quizId;
    }
    public function getScore(): int {
        return $this->score;
    }
    public function setScore(int $score) {
        $this->score = $score;
    }
    public function getDate(): DateTime {
        return $this->date;
    }
    public function setDate(DateTime $date) {
        $this->date = $date;
    }
}