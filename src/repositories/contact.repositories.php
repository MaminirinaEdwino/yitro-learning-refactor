<?php

require_once "./src/models/contact.php";
require_once "./src/config/database.php";

class ContactRepositories{
    private Database $database;

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
        $stmt = $conn->exec($query);
        return $result;
    }
}