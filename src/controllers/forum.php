<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/forum.repositories.php";
require_once "./src/repositories/post.repositories.php";
require_once "./src/models/forum.php";

$forumRouter = new Router();

$forumRouter->post("/forum/new", function(){
    $forumRepo = new ForumRepositories();
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description'] ?? '');
    $cours_id = (int)$_POST['cours_id'];
    $forum = new Forum($cours_id, $titre, $description);
    $forumRepo->Insert($forum);
    
    header("Location: /espace/apprenant");
    exit();
});

$forumRouter->get("/espace/apprenant/forum/:id", function(int $forum_id){
    $forumRepo = new ForumRepositories();
    $postRepo = new PostRepositories();

    $forum = $forumRepo->GetFromForumCours($forum_id);
    $posts = $postRepo->GetPostByForumUser($_SESSION['user_id'], $forum_id);

    TemplateRender::render("/forum/apprenantForum.php", [
        "forum_id"=>$forum_id,
        "forum"=>$forum,
        "posts"=>$posts
    ]);
});