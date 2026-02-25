<?php

class Post{
    private int $id;
    private int $auteurId;
    private int $forumId;
    private string $contenu;
    private DateTime $datePost;

    public function getId(): int {
        return $this->id;
    }
    public function getAUteurId(): int {
        return $this->auteurId;
    }
    public function setAuteurId(int $auteurId) {
        $this->auteurId = $auteurId;
    }
    public function getForumId(): int {
        return $this->forumId;
    }
    public function setForumId(int $forumId){
        $this->forumId = $forumId;
    }
    public function getDatePost(): DateTime {
        return $this->datePost;
    }
    public function setDatePost(DateTime $datePost) {
        $this->datePost = $datePost;
    }
    public function getContenu(): string {
        return $this->contenu;
    }
    public function setContenu(string $contenu) {
        $this->contenu = $contenu;
    }
}