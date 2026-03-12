<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";

$mentionLegaleRouter = new Router();

$mentionLegaleRouter->get("/mention-legale", function(){
    TemplateRender::render("/mentionLegale/mentionLegale.php", null);
});