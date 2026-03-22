<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/utilisateurs.repositories.php";
require_once "./src/repositories/formateur.repositories.php";


$adminRouter = new Router();
$adminRouter->get("/admin/login", function () {
    TemplateRender::render("/admin/login.php", null);
});

$adminRouter->post("/admin/login", function () {
    $userRepo = new UtilisateursRepositories();
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mot_de_passe = $_POST['mot_de_passe'];

    if (empty($email) || empty($mot_de_passe)) {
        header("Lcaltion: /admin/login");
        exit();
    } else {
        
        $result = $userRepo->GetForAuthAdmin($email);

        if ($result) {
            $user = $result;
            if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                header("Location: backoffice.php");
                exit();
            } else {
                $_SESSION['error'] = "Mot de passe incorrect.";
            }
        } else {
            $_SESSION['error'] = "Aucun compte administrateur trouvé avec cet email.";
        }
    }
});

$adminRouter->get("/admin/backoffice", function(){
    $userRepo = new UtilisateursRepositories();
    $formateurRepo = new FormateurRepositories();
    $newApprenant = $userRepo->GetNewApprenant();
    $newFormateur = $formateurRepo->GetNewFormateur();

    TemplateRender::render("/admin/backoffice.php", [
        "new_apprenant"=>$newApprenant,
        "new_formateurs"=>$newFormateur
    ]);
});