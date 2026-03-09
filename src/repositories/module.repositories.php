<?php

require_once "./src/config/database.php";
require_once "./src/models/module.php";

class ModuleRepositories {
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new Module(
                $donne["cours_id"],
                $donne["titre"],
                $donne["description"]
            );
            $var->setId($donne["id"]);
            array_push($result, $var);
        }
    }

    public function Insert(Module $module) {
        $query = "INSERT INTO module(cours_id, titre, description) VALUES(:cours_id, :titre, :description)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "cours_id"=>$module->getCoursId(),
            "titre"=>$module->getTitre(),
            "description"=>$module->getDescription()
        ]);
    }

    public function GetAll(): array {
        $result = [];
        $query = "SELECT * FROM module";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Module {
        $result = [];
        $query = "SELECT * FROM module WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$id]);
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function Update(Module $module) {
        $query = "UPDATE module SET cours_id = :cours_id, titre=:titre, description =:description WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "cours_id"=>$module->getCoursId(),
            "titre"=>$module->getTitre(),
            "description"=>$module->getDescription(),
            "id"=>$module->getId()
        ]);
    }

    public function Delete(Module $module) {
        $query = "DELETE FROM module WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $module->getId()
        ]);
    }
}