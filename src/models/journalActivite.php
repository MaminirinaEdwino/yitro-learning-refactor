<?php

class JournalActivite {
    private int $id;
    private int $adminId;
    private string $action;
    private string $details;
    private DateTime $createdAt;

    public function getId(): int {
        return $this->id;
    }
    
    public function getAdminId(): int {
        return $this->adminId;
    }
    public function setAdminId(int $adminId) {
        $this->adminId = $adminId;
    }

    public function getAction(): string {
        return $this->action;
    }
    public function setAction(string $action) {
        $this->action = $action;
    }

    public function getDetails(): string {
        return $this->details;
    }
    public function setDetails(string $details) {
        $this->details =  $details;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }
    public function setCreatedAt(DateTime $createdAt ){
        $this->createdAt = $createdAt;
    }
}