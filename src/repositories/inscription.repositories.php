<?php

require_once "./src/config/database.php";
require_once "./src/models/inscription.php";

class InscriptionRepositories
{
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
                $donne["statut_payement"],
                $donne["references_payement"],
                $donne["method_payement"]
            );
            $var->setDateInscription($donne['date_inscription']);
            $var->setId($donne["id"]);
            array_push($this->result, $var);
        }
    }

    public function Insert(Inscription $inscription): int
    {
        $query = "INSERT INTO inscription (utilisateur_id, cours_id, references_payement) VALUES(:utilisateur_id, :cours_id, :references_payement, :method_payement)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "utilisateur_id" => $inscription->getUtilisateurId(),
            "cours_id" => $inscription->getCoursId(),
            "references_payement"=>$inscription->getReferencePayement(),
            "method_payement"=>$inscription->getMethodPayement()
        ]);
        return $conn->lastInsertId();
    }

    public function GetAll(): array
    {
        $query = "SELECT * FROM inscription";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = [];
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Inscription
    {
        $query = "SELECT * FROM inscription WHERE id =:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $id]);
        $result = [];
        $this->PushArray($stmt, $result);
        return $result[0];
    }

    public function GetUserCoursInscription($utilisateur_id, $cours_id){
        $stmt = $this->database->getConnection()->prepare("SELECT COUNT(*) FROM inscriptions WHERE utilisateur_id = ? AND cours_id = ? AND statut_paiement IN ('paye', 'en_attente')");
        $stmt->execute([$utilisateur_id, $cours_id]);
        $count = $stmt->fetchColumn();
    }

    public function Update(Inscription $inscription)
    {
        $query = "UPDATE inscription SET utilisateur_id = :utilisateur_id, cours_id = :cours_id, statut_payement = :statut_payement WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "utilisateur_id" => $inscription->getUtilisateurId(),
            "cours_id" => $inscription->getCoursId(),
            "statut_payement" => $inscription->getStatutPayement(),
            "id" => $inscription->getId()
        ]);
    }

    public function DeleteByCoursId(int $id)
    {
        $stmtInscriptions = $this->database->getConnection()->prepare("DELETE FROM inscriptions WHERE cours_id = ?");
        $stmtInscriptions->execute([$id]);
    }

    public function Delete(Inscription $inscription)
    {
        $query = "DELETE FROM inscription WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $inscription->getId()]);
    }

    public function GetEnrolledCours(int $utilisateurId, $coursId): bool
    {
        $stmt = $this->database->getConnection()->prepare("SELECT * FROM inscriptions WHERE utilisateur_id = ? AND cours_id = ? AND statut_paiement = 'paye'");
        $stmt->execute([$utilisateurId, $coursId]);
        $is_enrolled = $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        return $is_enrolled;
    }
    public function GetProgressionApprenant(int $id): array
    {
        $stmt = $this->database->getConnection()->prepare("
        SELECT c.id AS cours_id, c.titre AS cours_titre, i.date_inscription
        FROM inscriptions i
        JOIN cours c ON i.cours_id = c.id
        WHERE i.utilisateur_id = ? AND i.statut_paiement = 'paye'
    ");
        $stmt->execute([$id]);
        $apprenant = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $apprenant;
    }

    public function GetApprenantByCoursId(int $coursId): array
    {
        $stmt = $this->database->getConnection()->prepare("
        SELECT u.id AS utilisateur_id, u.nom AS utilisateur_nom
        FROM inscriptions i
        JOIN utilisateurs u ON i.utilisateur_id = u.id
        WHERE i.cours_id = ? AND i.statut_paiement = 'paye'
    ");
        $stmt->execute([$coursId]);
        $apprenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $apprenants;
    }

    public function GetApprenantCours(int $id): array
    {
        $stmt = $this->database->getConnection()->prepare("
        SELECT c.id, c.titre
        FROM inscriptions i
        JOIN cours c ON i.cours_id = c.id
        WHERE i.utilisateur_id = ? AND i.statut_paiement = 'paye'
    ");
        $stmt->execute([$id]);
        $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $cours;
    }
}
