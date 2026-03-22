<?php

require_once "./src/router/router.php";
require_once "./src/repositories/journalActivite.repositories.php";

$journalRouter = new Router();
$journalRouter->post("/notification/read", function () {
    $_SESSION['notifications_read'] = true;
    $journalRepo = new JournalActiviteRepositories();
    $journalRepo->MarkRead();
    header("Location: /admin/backoffice");
    exit();
});
