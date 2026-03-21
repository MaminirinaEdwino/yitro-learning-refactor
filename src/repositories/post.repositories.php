<?php

require_once "./src/config/database.php";
require_once "./src/models/post.php";

class PostRepositories
{
    private Database $database;
    public function __construct()
    {
        $this->database = new Database();
    }
    private function PushArray($stmt, &$result)
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

    public function Insert(Post $post)
    {
        $query = "INSERT INTO post(auteur_id, forum_id, contenu) VALUES(:auteur_id, :forum_id, :contenu)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "auteur_id" => $post->getAUteurId(),
            "forum_id" => $post->getForumId(),
            "contenu" => $post->getContenu()
        ]);
    }

    public function GetAll(): array
    {
        $result = [];
        $query = "SELECT * FROM post";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Post
    {
        $result = [];
        $query = "SELECT * FROM post WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $id]);
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function Update(post $post)
    {
        $query = "UPDATE post SET auteur_id = :auteur_id, forum_id=:forum_id, contenu =:contenu WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "auteur_id" => $post->getAUteurId(),
            "forum_id" => $post->getForumId(),
            "contenu" => $post->getContenu(),
            "id" => $post->getId()
        ]);
    }

    public function Delete(Post $post)
    {
        $query = "DELETE FROM post WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $post->getId()
        ]);
    }

    public function GetPostByForumUser(int $current_user_id, int $forum_id): array
    {
        $stmt = $this->database->getConnection()->prepare("
        SELECT p.*, 
               COALESCE(f.nom_prenom, u.nom) AS auteur_nom,
               CASE 
                   WHEN p.auteur_id = ? THEN 1 
                   ELSE 0 
               END AS is_self,
               CASE 
                   WHEN f.id IS NOT NULL THEN 1 
                   ELSE 0 
               END AS is_formateur
        FROM post p 
        JOIN utilisateurs u ON p.auteur_id = u.id 
        LEFT JOIN formateurs f ON u.email = f.email 
        WHERE p.forum_id = ? 
        ORDER BY p.date_post ASC
    ");
        $stmt->execute([$current_user_id, $forum_id]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
    }

    public function GetPostFormateurIndicator(int $forum_id): array
    {
        $stmt = $this->database->getConnection()->prepare("
        SELECT p.id, p.contenu, p.date_post, COALESCE(f.nom_prenom, u.nom) AS auteur_nom,
               CASE WHEN f.id IS NOT NULL THEN 1 ELSE 0 END AS is_formateur
        FROM post p
        JOIN utilisateurs u ON p.auteur_id = u.id
        LEFT JOIN formateurs f ON u.email = f.email
        WHERE p.forum_id = ?
        ORDER BY p.date_post ASC
    ");
        $stmt->execute([$forum_id]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
    }
}
