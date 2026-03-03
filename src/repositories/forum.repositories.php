<?php

require_once "./src/config/database.php";
require_once "./src/models/forum.php";


class ForumRepositories{
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $forum = new Forum(
                $donne["nom_formation"]
            );
            $forum->setDateCreation($donne['date_creation']);
            $forum->setId($donne["id"]);
            array_push($result, $forum);
        }
    }

    public function Insert(Forum $forum){
        $query = "INSERT INTO forum (cours_id, titre, description) VALUES(:cours_id, :titre, :description)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "cours_id"=>$forum->getCoursId(),
            "titre"=>$forum->getTitre(),
            "description"=>$forum->getDescription()
        ]);
    }

    public function GetAll(): array {
        $query = "SELECT * FROM forum ";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = [];
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Forum {
        $query = "SELECT * FROM forum ";
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