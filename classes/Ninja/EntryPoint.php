<?php
namespace Ninja;

class EntryPoint{
    private $route;
    private $routes;
    private $method;

    public function __construct($route, $method, \Ninja\Routes $routes){
        $this->route = $route;
        $this->routes = $routes;
        $this->method = $method;
        $this->checUri();
    }

    private function checUri(){
        if ($this->route !== strtolower($this->route)){
            http_response_code(301);
            header('location: ' . strtolower($route));
        }
    }

    private function loadTemplate($templateFileName, $variables = []){ //avoid ele names in $variables overwrite the names
        extract($variables);                                                                // in try... block

        ob_start();
        include  __DIR__ . '/../../templates/' . $templateFileName;
        return ob_get_clean();
    }

    public function run(){
        $routes = $this->routes->getRoutes();
        $authentication = $this->routes->getAuthentication();
        if (isset($routes[$this->route]['login']) && !$authentication->isLoggedIn()){
            header('location: /login/error');
        }
        else{
            $controller = $routes[$this->route][$this->method]['controller'];
            $action = $routes[$this->route][$this->method]['action'];

            $page = $controller->$action();
            $title = $page['title'];

            if (isset($page['variables'])) {
                $output = $this->loadTemplate($page['template'], $page['variables']);
            }
            else {
                $output = $this->loadTemplate($page['template']);
            }

        //    include  __DIR__ . '/../../templates/layout.html.php';
            echo $this->loadTemplate('layout.html.php', ['loggedIn' => $authentication->isLoggedIn(),
                                                                                    'output' => $output,
                                                                                    'title' => $title,]);
        }
    }
}
