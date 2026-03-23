<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/forum.repositories.php";
require_once "./src/repositories/journalActivite.repositories.php";
require_once "./src/models/journalActivite.php";
require_once "./src/repositories/post.repositories.php";

$gestionForumRouter = new Router();
$gestionForumRouter->get("/gestion/forum", function () {
    $forumRepo = new FormationRepositories();
    TemplateRender::render("/admin/gestionForum.php", ["forums" => $forumRepo->GetForGestionForum()]);
});

$gestionForumRouter->post("/forum/delete/:id", function (int $forum_id) {
    $forumRepo = new ForumRepositories();
    $forum = $forumRepo->GetById($forum_id);
    $forumRepo->Delete($forum);

    $journalRepo = new JournalActiviteRepositories();
    $journalRepo->Insert(new JournalActivite($_SESSION["user_id"], "Delete Forum", "SUppression du forum " . $forum_id));

    header("Location: /gestion/forum");
    exit();
});

$gestionForumRouter->post("/forum/edit/", function () {
    $forum_id = $_POST["forum_id"];
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);

    $forumRepo = new ForumRepositories();
    $forum = $forumRepo->GetById($forum_id);
    $forum->setTitre($titre);
    $forum->setDescription($description);
    $forumRepo->Update($forum);

    $journalRepo = new JournalActiviteRepositories();
    $journalRepo->Insert(new JournalActivite($_SESSION["user_id"], "update forum", "modificaiton du forum " . $forum_id));
    header("Location: /gestion/forum");
    exit();
});


$gestionForumRouter->get("/gestion/forum/message/:id", function (int $forum_id) {
    $forumRepo = new ForumRepositories();
    $forum = $forumRepo->GetFromForumCours($forum_id);

    $postRepo = new PostRepositories();
    $posts = $postRepo->GetPostFormateurIndicator($forum_id);

    $journalRepo = new JournalActiviteRepositories();
    $journalRepo->Insert(new JournalActivite($_SESSION['user_id'], "Visualisation message", "Visualisation des messages"));
    
    TemplateRender::render("/admin/voirMessage.php", [
        "forums"=>$forum,
        "posts"=>$posts
    ]);
});
