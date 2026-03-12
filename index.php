<?php

session_start();
define('URL_ROOT', '/');

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/controllers/apropos.php";
require_once "./src/controllers/formation.php";
require_once "./src/controllers/mentionLegale.php";
$request_uri = $_SERVER['REQUEST_URI'];

$router = new Router();
$router->get("/", function(){
    TemplateRender::render("/home/home.php", null);
});

$router->includeRouter($aproposRouter);
$router->includeRouter($formationRouter);
$router->includeRouter($mentionLegaleRouter);
$router->dispatch($request_uri);