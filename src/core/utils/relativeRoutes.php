<?php
function To_relative_path($route ){
    $current_uri = $_SERVER['REQUEST_URI'];
    $depth = substr_count(trim(dirname($current_uri), '/'), '/');
    $relative_path_to_root = str_repeat('../', $depth); 
    return $relative_path_to_root.$route;
}
?>