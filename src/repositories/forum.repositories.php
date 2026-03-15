<?php

require_once "./src/config/database.php";
require_once "./src/models/forum.php";


class ForumRepositories
{
    private Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }
    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $forum = new Forum(
                $donne["cours_id"],
                $donne["titre"],
                $donne["description"]
            );
            $forum->setDateCreation($donne['date_creation']);
            $forum->setId($donne["id"]);
            array_push($result, $forum);
        }
    }

    public function Insert(Forum $forum)
    {
        $query = "INSERT INTO forum (cours_id, titre, description) VALUES(:cours_id, :titre, :description)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "cours_id" => $forum->getCoursId(),
            "titre" => $forum->getTitre(),
            "description" => $forum->getDescription()
        ]);
    }

    public function GetAll(): array
    {
        $query = "SELECT * FROM forum ";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = [];
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Forum
    {
        $query = "SELECT * FROM forum ";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $id
        ]);
        $result = [];
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function Update(Forum $forum)
    {
        $query = "UPDATE forum SET cours_id = :cours_id, titre = :titre, description=:description WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "cours_id" => $forum->getId(),
            "titre" => $forum->getTitre(),
            "description" => $forum->getDescription()
        ]);
    }

    public function Delete(Forum $forum)
    {
        $query = "DELETE FROM forum WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $forum->getId()]);
    }

    public function GetByCours(): array
    {
        $stmt = $this->database->getConnection()->prepare("SELECT f.*, c.titre AS cours_titre FROM forum f JOIN cours c ON f.cours_id = c.id");
        $stmt->execute();
        $forum = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $forum;
    }
}
