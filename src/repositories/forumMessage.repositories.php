<?php

require_once "./src/models/forumMessage.php";
require_once "./src/config/database.php";
class ForumMessageRepositories
{
    private Database $database;

    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $forum = new ForumMessage(
                $donne["cours_id"],
                $donne["utilisateur_id"],
                $donne["message"],
                $donne["lu"]
            );
            $forum->setDate($donne['date']);
            $forum->setId($donne["id"]);
            array_push($result, $forum);
        }
    }

    public function Insert(ForumMessage $forumMessage)
    {
        $query = "INSERT INTO forum_message(cours_id, utilisateur_id, message) VALUES (:cours_id, :utilisateur_id, :message)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "cours_id" => $forumMessage->getCoursId(),
            "utilisateur_id" => $forumMessage->getUtilisateurId(),
            "message" => $forumMessage->getMessage()
        ]);
    }

    public function GetAll(): array {
        $query = "SELECT * FROM forum_message";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = [];
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id) : ForumMessage {
        $query = "SELECT * FROM forum_message WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$id]);
        $result = [];
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function Update(ForumMessage $forumMessage) {
        $query = "UPDATE forum_message SET cours_id = :cours_id, utilisateur_id =:utilisateur_id, message = :message, lu = :lu";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "cours_id"=>$forumMessage->getCoursId(),
            "utilisateur_io"=>$forumMessage->getUtilisateurId(),
            "message"=>$forumMessage->getMessage(),
            "lu"=>$forumMessage->getLu()
        ]);
    }
    public function Delete(ForumMessage $forumMessage) {
        $query = "DELETE FROM forum_message WHERE id =:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id"=>$forumMessage->getId()
        ]);
    }
}
