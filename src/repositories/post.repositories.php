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

    

}