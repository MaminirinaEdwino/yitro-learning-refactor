<?php

session_start();
define('URL_ROOT', '/');

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/controllers/apropos.php";
require_once "./src/controllers/formation.php";
require_once "./src/controllers/mentionLegale.php";
require_once "./src/controllers/authentication.php";
require_once "./src/controllers/espaceApprenant.php";
require_once "./src/controllers/cours.php";
require_once "./src/controllers/quiz.php";
require_once "./src/controllers/forum.php";
require_once "./src/controllers/post.php";
require_once "./src/controllers/espaceFormateur.php";
require_once "./src/controllers/sousFormation.php";
require_once "./src/controllers/module.php";
require_once "./src/controllers/lecon.php";
require_once "./src/controllers/admin.php";
require_once "./src/controllers/journalactivite.php";
require_once "./src/controllers/utilisateurs.php";
require_once "./src/controllers/gestionFormation.php";

$request_uri = $_SERVER['REQUEST_URI'];

$router = new Router();
$router->get("/", function(){
    TemplateRender::render("/home/home.php", null);
});

$router->includeRouter($aproposRouter);
$router->includeRouter($formationRouter);
$router->includeRouter($mentionLegaleRouter);
$router->includeRouter($authRouter);
$router->includeRouter($espaceApprenantRouter);
$router->includeRouter($coursRouter);
$router->includeRouter($quizRouter);
$router->includeRouter($forumRouter);
$router->includeRouter($postRouter);
$router->includeRouter($espaceFormateurRouter);
$router->includeRouter($sousFormationRouter);
$router->includeRouter($moduleRouter);
$router->includeRouter($leconRouter);
$router->includeRouter($userRouter);
$router->includeRouter($journalRouter);
$router->includeRouter($adminRouter);
$router->includeRouter($gestionFormationRouter);

$router->dispatch($request_uri);