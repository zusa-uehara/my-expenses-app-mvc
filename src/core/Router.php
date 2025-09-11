<?php

class Router{

  private $routes;

  public function __construct($routes)
  {
    $this->routes = $routes;
  }

  public function resolve($pathInfo){
    foreach($this->routes as $path => $routePattern){
      if($path === $pathInfo){
        return $routePattern;
      }
    }
    return false;
  }

}
