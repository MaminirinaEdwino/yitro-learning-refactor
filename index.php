<?php

session_start();

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";


$request_uri = $_SERVER['REQUEST_URI'];

$router = new Router();
$router->get("/", function(){
    TemplateRender::render("/home/home.php", null);
});

$router->dispatch($request_uri);