<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/post.repositories.php";
require_once "./src/models/post.php";

$postRouter = new Router();
$postRouter->post("/post/new", function(){
    $contenu = trim($_POST['contenu']);
    $forum_id = (int)$_POST['forum_id'];
    $auteur_id = $_SESSION['user_id'];
    $postRepo = new PostRepositories();
    $post = new Post($auteur_id, $forum_id, $contenu);
    $postRepo->Insert($post);
    
    header("Location: /espace/apprenant/forum/$forum_id");
    exit();
});