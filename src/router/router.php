<?php
class Router
{
    private $routes = [];
    public function get(string $pattern, $action)
    {
        $this->routes['GET'][$pattern] = $action;
    }
    public function post(string $pattern, $action)
    {
        $this->routes['POST'][$pattern] = $action;
    }

    public function includeRouter(Router $router){
        foreach ($router->routes as $method=>$content) {
            foreach ($router->routes[$method] as $pattern => $action) {
                // echo $method;
                $this->routes[$method][$pattern] = $action;
            }
            // echo $content;
        }
    }   

    public function dispatch($uri)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $pattern => $action) {
                if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
                    if (!is_string($action)){
                        $action();
                        return;
                    }
                }
            }
            $exp_uri = explode("/", $uri);
            foreach ($this->routes[$method] as $pattern => $action) {
                $exp_pattern = explode("/", $pattern);
                if (count($exp_uri) == count($exp_pattern)){
                    if (count($exp_uri) == 4) {
                        if ($exp_uri[1] == $exp_pattern[1] && $exp_uri[2] == $exp_pattern[2]) {
                        if ($exp_uri[count($exp_uri) - 1] && $exp_pattern[count($exp_uri) - 1][0] == ":") {
                            if (!is_string($action)) {
                                $action($exp_uri[count($exp_uri) - 1]);   
                            }
                            return;
                        }
                    }
                    }else{
                        if ($exp_uri[1] == $exp_pattern[1] ) {
                        if ($exp_uri[count($exp_uri) - 1] && $exp_pattern[count($exp_uri) - 1][0] == ":") {
                            if (!is_string($action)) {
                                $action($exp_uri[count($exp_uri) - 1]);   
                            }
                            return;
                        }
                    }
                    }
                }
            }
        }
        require "./src/views/404/notfoundpage.php";
    }
}
