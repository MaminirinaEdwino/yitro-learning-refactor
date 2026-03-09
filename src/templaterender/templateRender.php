<?php

class TemplateRender{

    public static function render(string $path, $param){
        $params = $param;
        include_once "./src/views".$path;
    }
}