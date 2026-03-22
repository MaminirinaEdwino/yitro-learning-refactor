<?php

require_once './vendor/PHPMailer/src/Exception.php';
require_once './vendor/PHPMailer/src/PHPMailer.php';
require_once './vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/utilisateurs.repositories.php";
require_once "./src/repositories/formateur.repositories.php";
require_once "./src/repositories/cours.repositories.php";
require_once "./src/repositories/journalActivite.repositories.php";

$adminRouter = new Router();
$adminRouter->get("/admin/login", function () {
    TemplateRender::render("/admin/login.php", null);
});

$adminRouter->post("/admin/login", function () {
    $userRepo = new UtilisateursRepositories();
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mot_de_passe = $_POST['mot_de_passe'];

    if (empty($email) || empty($mot_de_passe)) {
        header("Location: /admin/login");
        exit();
    } else {

        $result = $userRepo->GetForAuthAdmin($email);

        if ($result) {
            $user = $result;
            if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                header("Location: /admin/backoffice");
                exit();
            } else {
                $_SESSION['error'] = "Mot de passe incorrect.";
            }
        } else {
            $_SESSION['error'] = "Aucun compte administrateur trouvé avec cet email.";
        }
    }
});

$adminRouter->get("/admin/backoffice", function () {
    $userRepo = new UtilisateursRepositories();
    $formateurRepo = new FormateurRepositories();
    $coursRepo = new CoursRepositories();
    $journalRepo = new JournalActiviteRepositories;

    $newApprenant = $userRepo->GetNewApprenant();
    $newFormateur = $formateurRepo->GetNewFormateur();
    $newCours = $coursRepo->GetNewCours();
    $inactive_users = $userRepo->GetInactiveUsers();
    $formateurs = $formateurRepo->GetFormateurId();
    $inscriptions = [];
    for ($i = 5; $i >= 0; $i--) {
        $mois = date('Y-m', strtotime("-$i months"));
        $inscriptions[$mois] = $userRepo->CountUserPerMonth($mois);
    }

    TemplateRender::render("/admin/backoffice.php", [
        "new_apprenant" => $newApprenant,
        "new_formateurs" => $newFormateur,
        "new_cours" => $newCours,
        "inactive_users" => $inactive_users,
        "formateurs" => $formateurs,
        "apprenant_count" => $userRepo->CountApprenant(),
        "formateurs_count" => $formateurRepo->CountFormateur(),
        "cours_count" => $coursRepo->CountCours(),
        "activite_log" => $journalRepo->CountLog(),
        "last_log" => $journalRepo->GetLastLog(),
        "inscriptions" => $inscriptions
    ]);
});

$adminRouter->post("/formateur/update/status/:id", function (int $id) {
    $statut = $_POST['statut'];
    $formateurRepo = new FormateurRepositories();

    $formateurRepo->UpdateStatus($id, $statut, $_SESSION['user_id']);
    header("Location: /admin/gestionuser");
    exit();
});

$adminRouter->get("/admin/gestionuser", function () {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
    $order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

    $sort_column = $sort;
    if ($sort == 'nom') {
        $sort_column_utilisateurs = 'nom';
        $sort_column_formateurs = 'nom_prenom';
    } else {
        $sort_column_utilisateurs = $sort;
        $sort_column_formateurs = $sort;
    }

    $userRepo = new UtilisateursRepositories();
    $formateurRepo = new FormateurRepositories();

    $users = $userRepo->GetUserGestionUser($search, $sort_column_utilisateurs, $order);
    $formateurs = $formateurRepo->GetForamteurGestionUser($search, $sort_column_formateurs, $order);
    $admins = $userRepo->GetAdminGestionUser($search, $sort_column_utilisateurs, $order);
    TemplateRender::render("/admin/gestionutilisateur.php", [
        "search" => $search,
        "sort" => $sort,
        "order" => $order,
        "users" => $users,
        "formateurs" => $formateurs,
        "admins" => $admins
    ]);
});


$adminRouter->get("/export/csv", function () {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
    $order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

    // $sort_column = $sort;
    if ($sort == 'nom') {
        $sort_column_utilisateurs = 'nom';
        $sort_column_formateurs = 'nom_prenom';
    } else {
        $sort_column_utilisateurs = $sort;
        $sort_column_formateurs = $sort;
    }

    $userRepo = new UtilisateursRepositories();
    $formateurRepo = new FormateurRepositories();

    $users = $userRepo->GetUserGestionUser($search, $sort_column_utilisateurs, $order);
    $formtrs = $formateurRepo->GetForamteurGestionUser($search, $sort_column_formateurs, $order);
    $admins = $userRepo->GetAdminGestionUser($search, $sort_column_utilisateurs, $order);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=utilisateurs.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Type', 'ID', 'Nom', 'Email', 'Statut/Actif']);

    foreach ($users as $user) {
        fputcsv($output, ['Apprenant', $user['id'], $user['nom'], $user['email'], $user['actif'] ? 'Actif' : 'Inactif']);
    }
    foreach ($formtrs as $formtr) {
        fputcsv($output, ['Formateur', $formtr['id'], $formtr['nom_prenom'], $formtr['email'], $formtr['statut']]);
    }
    foreach ($admins as $admin) {
        fputcsv($output, ['Administrateur', $admin['id'], $admin['nom'], $admin['email'], $admin['actif'] ? 'Actif' : 'Inactif']);
    }
    fclose($output);
    exit;
});


$adminRouter->post("/send/code", function () {
    $formateur_id = $_POST['id'];
    // Générer un code unique
    $code = bin2hex(random_bytes(8)); // Code aléatoire de 16 caractères

    $formateurRepo = new FormateurRepositories();
    $journalRepo = new JournalActiviteRepositories();
    $formateur = $formateurRepo->GetById($formateur_id);


    if ($formateur) {
        // Mettre à jour le code dans la base de données
        $formateurRepo->UpdateCode($formateur_id, $code);

        // Envoyer l'email avec PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'edwinomaminirina@gmail.com'; // Remplacez par votre adresse Gmail
            $mail->Password = 'bocp ppde sogn lrdv'; // Remplacez par le mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Désactiver le débogage
            $mail->SMTPDebug = 0; // 0 = désactivé
            $mail->Debugoutput = 'html'; // Sortie par défaut (non utilisée si SMTPDebug = 0)

            // Destinataire
            $mail->setFrom('edwinobig@gmail.com', 'Yitro Learning');
            $mail->addAddress($formateur->getEmail());

            // Contenu de l'email
            $mail->isHTML(true);
            $mail->Subject = 'Votre code d\'inscription à Yitro Learning';
            $mail->Body = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd;'>
                        <img src='https://yitro-consulting.com/wp-content/uploads/2024/02/Capture-decran-le-2024-02-19-a-16.39.58.png' alt='Yitro Learning' style='max-width: 150px;'>
                        <h2>Votre code d'inscription</h2>
                        <p>Bonjour,</p>
                        <p>Voici votre code d'inscription pour devenir formateur sur Yitro Learning :</p>
                        <p style='font-size: 24px; font-weight: bold; color: #28a745;'>$code</p>
                        <p>Utilisez ce code pour finaliser votre inscription sur <a href='https://yitro-learning.com/Page/inscription-formateur.php' style='color: #007bff;'>notre plateforme</a>.</p>
                        <p>Merci de rejoindre notre communauté !</p>
                        <p style='color: #888;'>L'équipe Yitro Learning</p>
                    </div>
                ";
            $mail->AltBody = "Bonjour,\n\nVoici votre code d'inscription pour devenir formateur sur Yitro Learning : $code\n\nUtilisez ce code pour finaliser votre inscription sur https://yitro-learning.com/Page/inscription-formateur.php.\n\nMerci de rejoindre notre communauté !\nL'équipe Yitro Learning";

            $mail->send();
            $success_message = "Code envoyé avec succès à {$formateur->getEmail()}.";

            $admin_id = $_SESSION["user_id"];
            // Journaliser l'action
            $journalRepo->Insert(new JournalActivite($admin_id, 'Envoi code formateur', "Formateur ID: $formateur_id, Email: {$formateur->getEmail()}, Code: $code"));

            error_log("Email envoyé à {$formateur['email']} avec code: $code");
        } catch (Exception $e) {
            $error_message = "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
            error_log("Erreur envoi email à {$formateur['email']}: {$mail->ErrorInfo}");
        }
    } else {
        $error_message = "Formateur non trouvé.";
        error_log("Formateur ID $formateur_id non trouvé");
    }
    header("Location: /admin/gestionuser");
    exit;
});

$adminRouter->get("/suivi/apprenant/:id", function (int $id) {
    $userRepo = new UtilisateursRepositories();

    TemplateRender::render("/admin/suiviApprenant.php", [
        "id"=>$id,
        "user"=>$userRepo->GetById($id)
    ]);
});

$adminRouter->get("/voir/user/:id", function(int $id){
    $userRepo = new UtilisateursRepositories();

    TemplateRender::render("/admin/voirApprenant.php", [
        "id"=>$id,
        "user"=>$userRepo->GetById($id)
    ]);
});
