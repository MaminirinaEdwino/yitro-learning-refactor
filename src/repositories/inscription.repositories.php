<?php

require_once "./src/config/database.php";
require_once "./src/models/inscription.php";

class InscriptionRepositories {
    private Database $database;
    private array $result;

    public function __construct()
    {
        $this->database = new Database();
    }
    
    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $var = new Inscription(
                $donne["utilisateur_id"],
                $donne["cours_id"],
                $donne["statut_payement"]
            );
            $var->setDateInscription($donne['date_inscription']);
            $var->setId($donne["id"]);
            array_push($this->result, $var);
        }
    }

    public function Insert(Inscription $inscription) {
        $query = "INSERT INTO inscription (utilisateur_id, cours_id) VALUES(:utilisateur_id, :cours_id)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "utilisateur_id"=>$inscription->getUtilisateurId(),
            "cours_id"=>$inscription->getCoursId()
        ]);
    }

    public function GetAll() : array {
        $query = "SELECT * FROM inscription";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = [];
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Inscription {
        $query = "SELECT * FROM inscription WHERE id =:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$id]);
        $result = [];
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function Update(Inscription $inscription)  {
        $query = "UPDATE inscription SET utilisateur_id = :utilisateur_id, cours_id = :cours_id, statut_payement = :statut_payement WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "utilisateur_id"=>$inscription->getUtilisateurId(),
            "cours_id"=>$inscription->getCoursId(),
            "statut_payement"=>$inscription->getStatutPayement(),
            "id"=>$inscription->getId()
        ]);
    }

    public function Delete(Inscription $inscription){
        $query = "DELETE FROM inscription WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id"=>$inscription->getId()]);
    }

    public function GetEnrolledCours(int $utilisateurId, $coursId): bool{
        $stmt = $this->database->getConnection()->prepare("SELECT * FROM inscriptions WHERE utilisateur_id = ? AND cours_id = ? AND statut_paiement = 'paye'");
        $stmt->execute([$utilisateurId, $coursId]);
        $is_enrolled = $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        return $is_enrolled;
    }
}