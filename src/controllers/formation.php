<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";

$formationRouter = new Router();
$formationRouter->get("/formation/catalogue", function(){
    TemplateRender::render("/formations/catalogue.php", null);
});