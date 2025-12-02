<?php

class Database{
    private $conn;

    private string $host = "localhost";
    private string $name = "yitrolearning";
    private string $user = "root";
    private string $password = "";

    public function __construct(){}

    public function getConnection():PDO{
        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf-8";
        return new PDO($dsn, $this->user, $this->password);
    }
}