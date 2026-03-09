<?php

require_once "./src/config/database.php";
require_once "./src/models/lecons.php";

class LeconRepositories
{
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new Lecons(
                $donne["module_id"],
                $donne["titre"],
                $donne["format"],
                $donne["fichier"]
            );
            $var->setId($donne["id"]);
            array_push($result, $var);
        }
    }

    public function Insert(Lecons $lecons)
    {
        $query = "INSERT INTO lecons (module_id, titre, format, fichier) VALUES(:module_id, :titre, :format, :fichier)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "module_id" => $lecons->getModuleId(),
            "titre" => $lecons->getTitre(),
            "format" => $lecons->getFormat(),
            "fichier" => $lecons->getFichier()
        ]);
    }

    public function GetAll(): array
    {
        $result = [];
        $query = "SELECT * FROM lecons";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }
    public function GetById(int $id): Lecons
    {
        $result = [];
        $query = "SELECT * FROM lecons WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $id]);
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function Update(Lecons $lecons)
    {
        $query = "UPDATE lecons SET module_id =:module_id, titre=:titre, format=:format, fichier=:fichier WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $lecons->getId(),
            "module_id"=>$lecons->getModuleId(),
            "titre"=>$lecons->getTitre(),
            "format"=>$lecons->getFormat(),
            "fichier"=>$lecons->getFichier()
        ]);
    }
    public function Delete(Lecons $lecons) {
        $query = "DELETE FROM lecons WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $lecons->getId()
        ]);
    }
}
