<?php
class Question {
    private int $id;
    private int $quizId;
    private string $texte;
    private string $reponseCorrecte;
    private string $responseIncorrecte1;
    private string $responseIncorrecte2;
    private string $responseIncorrecte3;

    public function getId(): int {
        return $this->id;
    }
    public function getQuizId(): int {
        return $this->quizId;
    }
    public function setQuizId(int $quizId) {
        $this->quizId = $quizId;
    }
    public function getTexte(): string {
        return $this->texte;
    }
    public function setTexte(string $texte) {
        $this->texte = $texte;
    }
    public function getReponseCorrecte(): string {
        return $this->reponseCorrecte;
    }
    public function setReponseCorrecte(string $reponseCorrecte) {
        $this->reponseCorrecte = $reponseCorrecte;
    }
    public function getReponseIncorrecte1(): string {
        return $this->responseIncorrecte1;
    }
    public function setReponseIncorrecte1(string $reponseIncorrecte) {
        $this->responseIncorrecte1 = $reponseIncorrecte;
    }
     public function getReponseIncorrecte2(): string {
        return $this->responseIncorrecte2;
    }
    public function setReponseIncorrecte2(string $reponseIncorrecte) {
        $this->responseIncorrecte2 = $reponseIncorrecte;
    }
    public function getReponseIncorrecte3(): string {
        return $this->responseIncorrecte3;
    }
    public function setReponseIncorrecte3(string $reponseIncorrecte) {
        $this->responseIncorrecte3 = $reponseIncorrecte;
    }
}