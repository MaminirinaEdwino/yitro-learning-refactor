<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";

$aproposRouter = new Router();
$aproposRouter->get("/apropos/decouvrir-yitro", function(){
    TemplateRender::render("/aPropos/decouvrirYitro.php", null);
});

$aproposRouter->get("/apropos/faq", function(){
    TemplateRender::render("/aPropos/faq.php", null);
});

$aproposRouter->get("/apropos/contact", function(){
    TemplateRender::render("/aPropos/contact.php", null);
});