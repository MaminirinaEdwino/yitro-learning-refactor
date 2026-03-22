<?php

require_once "./src/router/router.php";
require_once "./src/repositories/utilisateurs.repositories.php";
require_once "./src/repositories/journalActivite.repositories.php";

$userRouter = new Router();

$userRouter->post("/user/deactivate", function () {
    $user_id = (int)$_POST['deactivate_user_id'];
    $userRepo = new UtilisateursRepositories();
    $journalRepo = new JournalActiviteRepositories();

    $has_active_column = $userRepo->HasActiveCol();

    

    if ($has_active_column) {
        $userRepo->DeactiveUser($user_id);
        $journalRepo->DeactivateUser($user_id);
    } 
    header("Location: /admin/backoffice");
    exit();
});
