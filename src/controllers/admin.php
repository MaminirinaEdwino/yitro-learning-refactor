<?php

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
        "inscriptions"=>$inscriptions
    ]);
});
