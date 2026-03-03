<?php

require_once "./src/models/forumMessage.php";
require_once "./src/config/database.php";
class ForumMessageRepositories
{
    private Database $database;

    public function Insert(ForumMessage $forumMessage)
    {
        $query = "INSERT INTO forum_message(cours_id, utilisateur_id, message) VALUES (:cours_id, :utilisateur_id, :message)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "cours_id"=>$forumMessage->getCoursId(),
            "utilisateur_id"=>$forumMessage->getUtilisateurId(),
            "message"=>$forumMessage->getMessage()
        ]);
    }
}