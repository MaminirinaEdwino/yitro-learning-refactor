<?php

use LDAP\Result;

require_once "./src/models/completions.php";
require_once "./src/config/database.php";

class CompletionsRepositories extends Completions {
    private Database $database;
    private function PushResult($stmt, $result){
        while ($donne = $stmt->fetch()){
            $completion = new Completions($donne['utilisateur_id'], $donne['module_id'], $donne['cours_id']);
            $completion->setDateCompletion($donne['date_completion']);
            $completion->setId($donne["id"]);
            array_push($result, $completion);
        }
    }
    public function __construct()
    {
        $this->database = new Database;
        $this->database->getConnection();
    }
    public function Insert(Completions $completions) {
        $query = "INSERT INTO completions (utilisateur_id, module_id, cours_id) VALUES(:utilisateur_id, :module_id, :cours_id)";
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "utilisateur_id"=>$completions->getUtilisateurId(),
            "module_id"=>$completions->getModuleId(),
            "cours_id"=>$completions->getCoursId()
        ));
    }

    public function GetAll(){
        $query = "SELECT * FROM completions";
        $conn = $this->database->getConnection();
        $conn->exec($query);
    }

    public function GetByUtilisateur(int $utilisateurId): array {
        $query = "SELECT * FROM completions WHERE utilisateur_id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "id"=>$utilisateurId
        ));
        $result = [];
        $this->PushResult($stmt, $result);
        return $result;
    }
    public function GetByModuleId(int $module_id): array {
        $query = "SELECT * FROM completions WHERE module_id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "id"=>$module_id
        ));
        $result = [];
        $this->PushResult($stmt, $result);
        return $result;
    }
    public function GetByCoursId(int $cours_id): array {
        $query = "SELECT * FROM completions WHERE cours_id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "id"=>$cours_id
        ));
        $result = [];
        $this->PushResult($stmt, $result);
        return $result;
    }
    public function GetById(int $id): array {
        $query = "SELECT * FROM completions WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "id"=>$id
        ));
        $result = [];
        $this->PushResult($stmt, $result);
        return $result;
    }
}