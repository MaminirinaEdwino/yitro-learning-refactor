<?php

require_once "./src/models/contact.php";
require_once "./src/config/database.php";

class ContactRepositories{
    private Database $database;

    private function PushArray($stmt, $result) {
         while ($donne = $stmt->fetch()){
            $completion = new Contact($donne['nom'], $donne['email'], $donne['sujet'], $donne['message']);
            $completion->setCreatedAt($donne['created_at']);
            $completion->setId($donne["id"]);
            array_push($result, $completion);
        }
    }

    public function Insert(Contact $contact){
        $query = "INSERT INTO contact(nom, email, sujet, message) VALUES(:nom, :email, :sujet, :message)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "nom"=>$contact->getNom(),
            "email"=>$contact->getEmail(),
            "sujet"=>$contact->getSujet(),
            "message"=>$contact->getMessage()
        ));
    }

    public function GetAll(): array {
        $result = [];
        $query = "SELECT * FROM contact";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }
    public function GetById(int $id): Contact {
        $query = "SELECT * FROM contact WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "id"=>$id
        ));
        $donne = $stmt->fetch();
        $result = new Contact(
            $donne['nom'], $donne["email"], $donne["message"], $donne["sujet"]
        );
        return $result;
    }
    public function Update(Contact $contact){
        $query = "UPDATE contact SET nom = :nom, email= :email, sujet = :sujet, message = :message";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "nom"=>$contact->getNom(),
            "email"=>$contact->getEmail(),
            "sujet"=>$contact->getSujet(),
            "message"=>$contact->getMessage()
        ));
    }
    public function DeleteContact(Contact $contact){
        $query = "DELETE FROM contact WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(array(
            "id"=>$contact->getId()
        ));
    }
}