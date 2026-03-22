<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/journalActivite.repositories.php";

$journalRouter = new Router();
$journalRouter->post("/notification/read", function () {
    $_SESSION['notifications_read'] = true;
    $journalRepo = new JournalActiviteRepositories();
    $journalRepo->MarkRead();
    header("Location: /admin/backoffice");
    exit();
});

$journalRouter->get("/journal", function () {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
    $order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';
    $journalRepo = new JournalActiviteRepositories();
    $activite = $journalRepo->GetFilterLog($search, $sort, $order);

    TemplateRender::render("/journal/list.php", [
        "activites" => $activite,
        "sort" => $sort,
        "search" => $search,
        "order" => $order
    ]);
});

$journalRouter->post("/journal/delete/:id", function (int $id) {
    $journalRepo = new JournalActiviteRepositories();
    $journal = $journalRepo->GetById($id);
    $journalRepo->Delete($journal);
    
    header("Location: /journal");
    exit;
});
