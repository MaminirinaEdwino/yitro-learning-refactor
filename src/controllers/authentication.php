<?php


require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";

$authRouter = new Router();

$authRouter->get("/connexion", function(){
    TemplateRender::render("/authentication/connexion.php", null);
});

$authRouter->get("/inscription", function(){
    TemplateRender::render("/authentication/inscription.php", null);
});