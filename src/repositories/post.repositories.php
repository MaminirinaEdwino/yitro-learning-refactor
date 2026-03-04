<?php

require_once "./src/config/database.php";
require_once "./src/models/post.php";

class PostRepositories{
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new Post(
                $donne["auteur_id"],
                $donne["forum_id"],
                $donne["contenu"]
            );
            $var->setId($donne["id"]);
            array_push($result, $var);
        }
    }

    public function Insert(Post $post) {
        $query = "INSERT INTO post(auteur_id, forum_id, contenu) VALUES(:auteur_id, :forum_id, :contenu)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "auteur_id"=>$post->getAUteurId(),
            "forum_id"=>$post->getForumId(),
            "contenu"=>$post->getContenu()
        ]);
    }

    public function GetAll(): array {
        $result = [];
        $query = "SELECT * FROM post";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Post {
        $result = [];
        $query = "SELECT * FROM post WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$id]);
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function Update(post $post) {
        $query = "UPDATE post SET auteur_id = :auteur_id, forum_id=:forum_id, contenu =:contenu WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "auteur_id"=>$post->getAUteurId(),
            "forum_id"=>$post->getForumId(),
            "contenu"=>$post->getContenu(),
            "id"=>$post->getId()
        ]);
    }

    public function Delete(Post $post) {
        $query = "DELETE FROM post WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $post->getId()
        ]);
    }

}