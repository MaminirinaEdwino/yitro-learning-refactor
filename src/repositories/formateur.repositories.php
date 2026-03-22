<?php

require_once "./src/config/database.php";
require_once "./src/models/formateur.php";

class FormateurRepositories
{
    private Database $database;
    private array $result;
    public function __construct()
    {
        $this->database = new Database();
    }
    private function PushArray($stmt, $result)
    {
        $this->result = [];
        while ($donne = $stmt->fetch()) {
            $formateur = new Formateur(
                $donne['nom_prenom'],
                $donne['email'],
                $donne['telephone'],
                $donne["ville_pays"],
                $donne["linkedin"],
                $donne["intitule_metier"],
                $donne["experience_formation"],
                $donne["detail_experience"],
                $donne["cv"],
                $donne["categories"],
                $donne["titre_cours"],
                $donne["autre_domaine"],
                $donne["objectif"],
                $donne["public_cible"],
                $donne["detail_complementaire"],
                $donne["formats"],
                $donne["format_autre"],
                $donne["duree_estimee"],
                $donne["type_formation"],
                $donne["motivation"],
                $donne["valeurs"],
                $donne["profil_public"],
                $donne["statut"]
            );
            $formateur->setCreatedAt(new DateTime($donne['created_at']));
            $formateur->setId($donne["id"]);
            array_push($this->result, $formateur);
        }
    }

    public function Insert(Formateur $formateur)
    {
        $query = "INSERT INTO formateurs(nom_prenom, email, telephone,ville_pays, linkedin, intitule_metier, experience_formation, detail_experience, cv, categories, autre_domaine, titre_cours, objectif, public_cible, detail_complementaire, formats, format_autres, duree_estimee, type_formation, motivation, valeurs, profil_public, statut) VALUES (:nom_prenom, :email, :telephone, :ville_pays, :linkedin, :intitule_metier, :experience_formation, :detail_experience, :cv, :categories, :autre_domaine, :titre_cours, :objectif, :public, :detail_complementaire, :formats, :format_autres, :duree_estimee, :type_formation, :motivation, :valeurs, :profil_public, :statut)";


        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "nom_prenom" => $formateur->getNomPrenom(),
            "email" => $formateur->getEmail(),
            "telephone" => $formateur->getTelephone(),
            "linkedin" => $formateur->getLinkedin(),
            "ville_pays" => $formateur->getVillePays(),
            "intitule_metier" => $formateur->getIntituleMetier(),
            "experience_formation" => $formateur->getExperienceFormation(),
            "detail_experience" => $formateur->getDetailExperience(),
            "cv" => $formateur->getCv(),
            "categories" => $formateur->getCategories(),
            "autre_domaine" => $formateur->getAutreDomain(),
            "titre_cours" => $formateur->getTitreCours(),
            "objectif" => $formateur->getObjectif(),
            "public_cible" => $formateur->getPublicCible(),
            "detail_complementaire" => $formateur->getDetailComplementaire(),
            "formats" => $formateur->getFormats(),
            "format_autre" => $formateur->getFormatAutres(),
            "duree_estimee" => $formateur->getDureeEstime(),
            "type_formation" => $formateur->getTypeFormation(),
            "motivation" > $formateur->getMotivation(),
            "valeurs" => $formateur->getValeur(),
            "profil_public" => $formateur->getProfilPublic(),
            "statut" => $formateur->getStatut()
        ]);
    }

    public function GetAll(): array
    {
        $result = [];
        $query = "SELECT * FROM formateurs";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function GetById(int $id): Formateur
    {
        $result = [];
        $query = "SELECT * FROM formateurs WHERE id =:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $id]);
        $this->PushArray($stmt, $result);

        return $this->result[0];
    }

    public function Delete(Formateur $formateur)
    {
        $query = "DELETE FROM formateurs WHERE id =:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $formateur->getId()]);
    }

    public function Update(Formateur $formateur)
    {
        $query = "UPDATE formateurs SET nom_prenom =:nom_prenom, email =:email, telephone =:telephone, ville_pays =:ville_pays, linkedin =:linkedin, intitule_metier =:intitule_metier, experience_formation =:experience_formation, detail_experience =:detail_experience, cv =:cv, categories=:categories, autre_domaine =:autre_domaine, titre_cours=:titre_cours, objectif =:objectif, public_cible =:public_cible, detail_complementaire =:detail_complementaire, formats =:formats, format_autre =:format_autre, duree_estimee =:duree_estimee, type_formation =:type_formation, motivation =:motivation, valeurs =:valeurs, profil_public=:profil_public, statut=:statut WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "nom_prenom" => $formateur->getNomPrenom(),
            "email" => $formateur->getEmail(),
            "telephone" => $formateur->getTelephone(),
            "linkedin" => $formateur->getLinkedin(),
            "ville_pays" => $formateur->getVillePays(),
            "intitule_metier" => $formateur->getIntituleMetier(),
            "experience_formation" => $formateur->getExperienceFormation(),
            "detail_experience" => $formateur->getDetailExperience(),
            "cv" => $formateur->getCv(),
            "categories" => $formateur->getCategories(),
            "autre_domaine" => $formateur->getAutreDomain(),
            "titre_cours" => $formateur->getTitreCours(),
            "objectif" => $formateur->getObjectif(),
            "public_cible" => $formateur->getPublicCible(),
            "detail_complementaire" => $formateur->getDetailComplementaire(),
            "formats" => $formateur->getFormats(),
            "format_autre" => $formateur->getFormatAutres(),
            "duree_estimee" => $formateur->getDureeEstime(),
            "type_formation" => $formateur->getTypeFormation(),
            "motivation" > $formateur->getMotivation(),
            "valeurs" => $formateur->getValeur(),
            "profil_public" => $formateur->getProfilPublic(),
            "statut" => $formateur->getStatut(),
            "id" => $formateur->getId()
        ]);
    }

    public function GetForAuth(string $email): array
    {
        $stmt = $this->database->getConnection()->prepare("SELECT id, email, password, nom_prenom FROM formateurs WHERE email = ? AND password IS NOT NULL");
        $stmt->execute([$email]);
        $formateur = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($formateur)) {
            return $formateur;
        }
        return [];
    }

    public function GetNewFormateur(): int
    {
        $stmt = $this->database->getConnection()->prepare("SELECT COUNT(*) as count FROM formateurs WHERE created_at >= NOW() - INTERVAL 1 DAY");
        $stmt->execute();
        $new_formateurs = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        return $new_formateurs;
    }

    public function GetFormateurId(): array
    {
        $stmt = $this->database->getConnection()->query("SELECT id FROM formateurs");
        $formateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $formateurs;
    }

    public function CountFormateur(): int
    {
        $stmt = $this->database->getConnection()->query("SELECT COUNT(*) as count FROM formateurs");
        $formateurs_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        return $formateurs_count;
    }

    public function GetForamteurGestionUser($search, $sort_column_formateurs, $order): array
    {
        $where = $search ? "WHERE nom_prenom LIKE ? OR email LIKE ?" : "";
        $sql = "SELECT * FROM formateurs $where ORDER BY $sort_column_formateurs $order";
        $stmtFormtr = $this->database->getConnection()->prepare($sql);
        if ($search) {
            $searchTerm = "%$search%";
            $stmtFormtr->execute([$searchTerm, $searchTerm]);
        } else {
            $stmtFormtr->execute();
        }
        $formtrs = $stmtFormtr->fetchAll();
        return $formtrs;
    }

    public function UpdateStatus(int $id, int $statut, int $admin_id)
    {
        $stmt = $this->database->getConnection()->prepare("UPDATE formateurs SET statut = ? WHERE id = ?");
        $stmt->execute([$statut, $id]);
        // Journaliser l'action
        $stmt = $this->database->getConnection()->prepare("INSERT INTO journal_activite (admin_id, action, details) VALUES (?, ?, ?)");
        $stmt->execute([$admin_id, 'Mise à jour statut formateur', "Formateur ID: $id, Statut: $statut"]);
    }

    public function UpdateCode(int $formateur_id, $code)
    {
        $stmt = $this->database->getConnection()->prepare("UPDATE formateurs SET code_entree = ? WHERE id = ?");
        $stmt->execute([$code, $formateur_id]);
    }
}
