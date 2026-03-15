<?php

require_once "./src/config/database.php";
require_once "./src/models/cours.php";

class CoursRepositories
{
    private Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }
    private function PushArray($stmt, $result)
    {
        while ($donne = $stmt->fetch()) {
            $completion = new Cours(
                $donne["formateur_id"],
                $donne["formation_id"],
                $donne["contenue_formation_id"],
                $donne["titre"],
                $donne["description"],
                $donne["prix"],
                $donne["photo"],
                $donne["niveau"]
            );
            $completion->setCreatedAt($donne['created_at']);
            $completion->setId($donne["id"]);
            array_push($result, $completion);
        }
    }

    public function Insert(Cours $cours)
    {
        $query = "INSERT INTO cours (formateur_id, formation_id, contenue_formation_id, titre, description, prix, photo, niveau) VALUES(:formateur_id, :formation_id, :contenue_formation_id, :titre, :description, :prix, :photo, :niveau)";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "formateur_id" => $cours->getIdFormateur(),
            "formation_id" => $cours->getIdFormation(),
            "contenue_formation_id" => $cours->getIdContenuFormation(),
            "titre" => $cours->getTitre(),
            "description" => $cours->getDescription(),
            "prix" => $cours->getPrix(),
            "niveau" => $cours->getNiveau()
        ]);
    }

    public function GetAll(): array
    {
        $result = [];
        $query = "SELECT * FROM cours";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Cours
    {
        $query = "SELECT * FROM cours WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $id
        ]);
        $donne = $stmt->fetch();
        $result = new Cours(
            $donne["formateur_id"],
            $donne["formation_id"],
            $donne["contenue_formation_id"],
            $donne["titre"],
            $donne["description"],
            $donne["prix"],
            $donne["photo"],
            $donne["niveau"]
        );
        $result->setId($id);
        $result->setCreatedAt($donne["created_at"]);
        return $result;
    }
    public function Update(Cours $cours)
    {
        $query = "UPDATE cours SET formateur_id = :formateur_id, formation_id = :formation_id, contenue_formation_id = :contenue_formation_id, titre = :titre, description =:description, prix =:prix, photo=:photo, niveau=:niveau WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "formateur_id" => $cours->getIdFormateur(),
            "formation_id" => $cours->getIdFormation(),
            "contenue_formation_id" => $cours->getIdContenuFormation(),
            "titre" => $cours->getTitre(),
            "description" => $cours->getDescription(),
            "prix" => $cours->getPrix(),
            "photo" => $cours->getPhoto(),
            "niveau" => $cours->getNiveau()
        ]);
    }

    public function Delete(Cours $cours)
    {
        $query = "DELETE FROM cours WHERE id =:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $cours->getId()
        ]);
    }

    public function GetCoursCatalogue(): array
    {
        $result = [];
        $query = "
        SELECT 
            c.id, 
            c.titre, 
            c.description, 
            c.prix, 
            c.niveau,
            c.photo,
            c.formation_id,         
            c.contenu_formation_id,
            f.nom_formation AS nom_theme,
            cf.sous_formation AS nom_sous_theme
        FROM 
            cours c
        LEFT JOIN   
            formations f ON c.formation_id = f.id_formation
        LEFT JOIN  
            contenu_formations cf ON c.contenu_formation_id = cf.id_contenu
        ORDER BY 
            c.titre ASC";

        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        while ($donne = $stmt->fetch()) {
            array_push($result, $donne);
        }
        return $result;
    }

    public function GetCoursFormation(): array
    {
        $result = [];
        $query = "SELECT c.*, f.id_formation FROM cours c LEFT JOIN formations f ON c.formation_id = f.id_formation";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function GetCoursProgression(): array
    {
        $stmt = $this->database->getConnection()->prepare("
    SELECT c.id, c.titre,
           COUNT(DISTINCT q.id) AS total_quiz,
           COUNT(DISTINCT CASE WHEN rq.score >= q.score_minimum THEN rq.id END) AS quiz_reussis
    FROM inscriptions i
    JOIN cours c ON i.cours_id = c.id
    LEFT JOIN modules m ON m.cours_id = c.id
    LEFT JOIN quiz q ON q.module_id = m.id
    LEFT JOIN resultats_quiz rq ON rq.quiz_id = q.id AND rq.utilisateur_id = :rq_id
    WHERE i.utilisateur_id = :id AND i.statut_paiement = 'paye'
    GROUP BY c.id, c.titre
");
        $stmt->execute([
            "rq_id" => $_SESSION['user_id'], 
            "id" => $_SESSION['user_id']
        ]);
        $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $cours;
    }
}
