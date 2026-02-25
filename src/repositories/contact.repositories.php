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
    // public function UpdateContact(Contact $contact){
    //     $query = "UPDATE contact SET nom = :nom, "
    // }
}