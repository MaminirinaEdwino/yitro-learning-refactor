<?php

require_once "./src/config/database.php";
require_once "./src/models/formation.php";

class FormationRepositories
{
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $formation = new Formation(
                $donne["nom_formation"]
            );
            $formation->setCreated_at($donne['created_at']);
            $formation->setIdFormation($donne["id_formation"]);
            array_push($result, $formation);
        }
    }

    public function Insert(Formation $formation) {
        $query = "INSERT INTO formations (nom_formation) VALUES(:formation)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["nom_formation"=>$formation->getNom_formation()]);
    }

    public function Delete(Formation $formation) {
        $query = "DELETE FROM formations WHERE id_formation = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$formation->getId_formation()]);
    }

    public function Update(Formation $formation) {
        $query = "UPDATE formations SET nom_formation =:nom_formation WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id"=>$formation->getId_formation(),
            "nom_formation"=>$formation->getNom_formation()
        ]);
    }

    public function GetAll(): array {
        $query = "SELECT * FROM formations ";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = [];
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Formation {
        $query = "SELECT * FROM formations WHERE id_formation =:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id"=>$id
        ]);
        $result = [];
        $this->PushArray($stmt, $result);
        return $result[0];
    }
}
