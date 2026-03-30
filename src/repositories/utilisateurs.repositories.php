<?php

require_once "./src/config/database.php";
require_once "./src/models/utilisateur.php";

class UtilisateursRepositories
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
            $var = new Utilisateur(
                $donne["nom"],
                $donne["email"],
                $donne["mot_de_passe"],
                $donne["telephone"] == null ? "" : $donne["telephone"],
                $donne["photo"] == null ? "" : $donne["photo"],
                $donne["pays"]  == null ? "" : $donne["pays"],
                $donne["langue"]  == null ? "" : $donne["langue"],
                $donne["objectifs"]  == null ? "" : $donne["objectifs"],
                $donne["autre_langue"] == null ? "" : $donne["autre_langue"],
                $donne["type_cours"] == null ? "" : $donne["type_cours"],
                $donne["niveau_formation"] == null ? "" : $donne["niveau_formation"],
                $donne["niveau_etude"] == null ? "" : $donne["niveau_etude"],
                $donne["acces_internet"] == null ? "" : $donne["acces_internet"],
                $donne["appareil"] == null ? "" : $donne["appareil"],
                $donne["accessibilite"] == null ? "" : $donne["accessibilite"],
                $donne["rgpd"] == null ? "" : $donne["rgpd"],
                $donne["charte"] == null ? "" : $donne["charte"],
                $donne["role"] == null ? "" : $donne["role"]
            );
            $var->setId($donne["id"]);
            $var->setCreatedAt(new DateTime($donne["created_at"]));
            $var->setActif($donne["actif"]);
            array_push($this->result, $var);
        }
    }
    public function GetActiveUser(): array
    {
        $stmt = $this->database->getConnection()->prepare("
    SELECT u.id, u.nom, u.email
    FROM utilisateurs u
    WHERE u.role = 'apprenant' AND u.actif = 1
    ORDER BY u.nom ASC
");
        $stmt->execute();
        $apprenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $apprenants;
    }

    public  function GetInfoUserCertificat(int $apprenant_id, int $cours_id): array
    {
        $stmt = $this->database->getConnection()->prepare("
            SELECT u.nom, c.titre
            FROM utilisateurs u
            JOIN inscriptions i ON u.id = i.utilisateur_id
            JOIN cours c ON i.cours_id = c.id
            WHERE u.id = ? AND c.id = ? AND i.statut_paiement = 'paye'
        ");
        $stmt->execute([$apprenant_id, $cours_id]);
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        return $info;
    }

    public function Insert(Utilisateur $utilisateur): bool
    {
        $query = "INSERT INTO utilisateurs(
        nom, 
        email,
        mot_de_passe,
        telephone, 
        photo,
        pays,
        langue,
        objectifs,
        autre_langue,
        type_cours,
        niveau_formation,
        niveau_etude,
        acces_internet,
        appareil,
        accessibilite,
        rgpd,
        charte,
        role ) VALUES(
        :nom, 
        :email,
        :mot_de_passe,
        :telephone, 
        :photo,
        :pays,
        :langue,
        :objectifs,
        :autre_langue,
        :type_cours,
        :niveau_formation,
        :niveau_etude,
        :acces_internet,
        :appareil,
        :accessibilite,
        :rgpd,
        :charte,
        :role
        )";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        return $stmt->execute([
            "nom" => $utilisateur->getNom(),
            "email" => $utilisateur->getEmail(),
            "mot_de_passe" => $utilisateur->getMdp(),
            "telephone" => $utilisateur->getTelephone(),
            "photo" => $utilisateur->getPhoto(),
            "pays" => $utilisateur->getPays(),
            "langue" => $utilisateur->getLangue(),
            "objectifs" => $utilisateur->getObjectif(),
            "autre_langue" => $utilisateur->getAutreLangue(),
            "type_cours" => $utilisateur->getTypeCours(),
            "niveau_formation" => $utilisateur->getNiveauFormation(),
            "niveau_etude" => $utilisateur->getNiveauEtude(),
            "acces_internet" => $utilisateur->getAccesInternet(),
            "appareil" => $utilisateur->getAppareil(),
            "accessibilite" => $utilisateur->getAccessibilite(),
            "rgpd" => $utilisateur->getRgpd(),
            "charte" => $utilisateur->getCharte(),
            "role" => $utilisateur->getRole()
        ]);
    }

    public function GetAll(): array
    {
        $result = [];
        $query = "SELECT * FROM utilisateurs";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->PushArray($stmt, $result);
        return $result;
    }

    public function HasActiveCol(): bool
    {
        $stmt = $this->database->getConnection()->prepare("SHOW COLUMNS FROM utilisateurs LIKE 'actif'");
        $stmt->execute();
        $has_active_column = $stmt->rowCount() > 0;
        return $has_active_column;
    }

    public function DeactiveUser(int $userId)
    {
        $stmt = $this->database->getConnection()->prepare("UPDATE utilisateurs SET actif = 0 WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public function CountUserPerMonth(string $mois): int
    {
        $stmt = $this->database->getConnection()->prepare("SELECT COUNT(*) as count FROM utilisateurs WHERE role = 'apprenant' AND DATE_FORMAT(created_at, '%Y-%m') = ?");
        $stmt->execute([$mois]);
        $inscriptions = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
        return $inscriptions;
    }

    public function GetNewApprenant(): int
    {
        $stmt = $this->database->getConnection()->prepare("SELECT COUNT(*) as count FROM utilisateurs WHERE role = 'apprenant' AND created_at >= NOW() - INTERVAL 1 DAY");
        $stmt->execute();
        $new_apprenants = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        return $new_apprenants;
    }

    public function GetInactiveUsers(): array
    {
        $inactive_users = [];
        $stmt = $this->database->getConnection()->prepare("
        SELECT u.id, u.nom, u.email, u.role, u.actif, MAX(COALESCE(p.date_post, u.created_at)) as last_activity
        FROM utilisateurs u
        LEFT JOIN post p ON u.id = p.auteur_id
        GROUP BY u.id
        HAVING last_activity < NOW() - INTERVAL 30 DAY
        ORDER BY last_activity ASC
        LIMIT 5
    ");
        $stmt->execute();
        $inactive_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $inactive_users;
    }

    public function GetForAuth(string $email): Utilisateur
    {
        $query = "SELECT * FROM utilisateurs WHERE email = :email";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "email" => $email
        ]);
        $this->PushArray($stmt, null);

        return $this->result[0];
    }

    public function GetForAuthAdmin(string $email): array
    {
        $query = "SELECT id, email, mot_de_passe, role FROM utilisateurs WHERE email = :email AND role = 'admin'";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "email" => $email
        ]);
        $admin = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $admin[0];
    }

    public function GetById(int $id): Utilisateur
    {
        $result = [];
        $query = "SELECT * FROM utilisateurs WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $id]);
        $this->PushArray($stmt, $result);
        return $this->result[0];
    }

    public function GetbyEmail(string $email): array {
         $result = [];
        $query = "SELECT email FROM utilisateurs WHERE email=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $email]);
        $this->PushArray($stmt, $result);
        return $this->result;
    }

    public function Update(Utilisateur $utilisateur)
    {
        $query = "UPDATE utilisateurs SET  
        nom = :nom, 
        email = :email,
        mot_de_passe = :mot_de_passe,
        telephone = :telephone, 
        photo = :photo,
        pays = :pays,
        langue = :langue,
        objectifs = :objectifs,
        autre_langue = :autre_langue,
        type_cours = :type_cours,
        niveau_formation = :niveau_formation,
        niveau_etude = :nivea_etude,
        acces_internet = :acces_internet,
        appareil = :appareil,
        accessibilite = :accessibilite,
        rgpd = :rgpd,
        charte :charte,
        role = :role
        WHERE id=:id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "nom" => $utilisateur->getNom(),
            "email" => $utilisateur->getEmail(),
            "mot_de_passe" => $utilisateur->getMdp(),
            "telephone" => $utilisateur->getTelephone(),
            "photo" => $utilisateur->getPhoto(),
            "pays" => $utilisateur->getPays(),
            "langue" => $utilisateur->getLangue(),
            "objectifs" => $utilisateur->getObjectif(),
            "autre_langue" => $utilisateur->getAutreLangue(),
            "type_cours" => $utilisateur->getTypeCours(),
            "niveau_formation" => $utilisateur->getNiveauFormation(),
            "niveau_etude" => $utilisateur->getNiveauEtude(),
            "acces_internet" => $utilisateur->getAccesInternet(),
            "appareil" => $utilisateur->getAppareil(),
            "accessibilite" => $utilisateur->getAccessibilite(),
            "rgpd" => $utilisateur->getRgpd(),
            "charte" => $utilisateur->getCharte(),
            "role" => $utilisateur->getRole(),
            "id" => $utilisateur->getId()
        ]);
    }

    public function Delete(Utilisateur $utilisateur)
    {
        $query = "DELETE FROM utilisateurs WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "id" => $utilisateur->getId()
        ]);
    }

    public function CountApprenant(): int
    {
        $stmt = $this->database->getConnection()->query("SELECT COUNT(*) as count FROM utilisateurs WHERE role = 'apprenant'");
        $apprenants_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        return $apprenants_count;
    }

    public function ToggleActive(int $admin_id, int $id)
    {
        $stmt = $this->database->getConnection()->prepare("UPDATE utilisateurs SET actif = !actif WHERE id = ?");
        $stmt->execute([$id]);
        $stmt = $this->database->getConnection()->prepare("INSERT INTO journal_activite (admin_id, action, details) VALUES (?, ?, ?)");
        $stmt->execute([$admin_id, 'Toggle actif utilisateur', "Utilisateur ID: $id"]);
    }

    public function GetUserGestionUser($search, $sort_column_utilisateurs, $order): array
    {
        $where = $search ? "WHERE role = 'apprenant' AND (nom LIKE ? OR email LIKE ?)" : "WHERE role = 'apprenant'";
        $sql = "SELECT * FROM utilisateurs $where ORDER BY $sort_column_utilisateurs $order";
        $stmtUser = $this->database->getConnection()->prepare($sql);
        if ($search) {
            $searchTerm = "%$search%";
            $stmtUser->execute([$searchTerm, $searchTerm]);
        } else {
            $stmtUser->execute();
        }
        $users = $stmtUser->fetchAll();
        return $users;
    }

    public function GetAdminGestionUser($search, $sort_column_utilisateurs, $order): array
    {
        $where = $search ? "WHERE role IN ('admin', 'moderator') AND (nom LIKE ? OR email LIKE ?)" : "WHERE role IN ('admin', 'moderator')";
        $sql = "SELECT * FROM utilisateurs $where ORDER BY $sort_column_utilisateurs $order";
        $stmtAdmin = $this->database->getConnection()->prepare($sql);
        if ($search) {
            $searchTerm = "%$search%";
            $stmtAdmin->execute([$searchTerm, $searchTerm]);
        } else {
            $stmtAdmin->execute();
        }
        $admins = $stmtAdmin->fetchAll();
        return $admins;
    }
}
