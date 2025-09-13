<?php

  class Application{

    protected $router;
    protected $request;
    protected $response;
    protected $databaseManager;

	public function __construct(){

	    $this->router = new Router($this->registerRoutes());
	    $this->request = new Request();
	    $this->response = new Response();
	    $this->databaseManager = new DatabaseManager();

	    $this->databaseManager->connect([
        'hostname' => getenv('DB_HOST'),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'database' => getenv('DB_NAME'),
	    ]);

      $this->databaseManager->initDatabase();
	}

    public function run(){

      try{
        $params = $this->router->resolve($this->request->getPathInfo());
        if(!$params){
          throw new HttpNotFoundException();
        }

        $controller = $params['controller'];
        $action = $params['action'];
        $this->runAction($controller, $action);
      } catch(HttpNotFoundException) {
        $this->render404page();
      }
        $this->response->send();

    }

    public function getRequest(){
      return $this->request;
    }

    public function getDatabaseManager(){
      return $this->databaseManager;
    }

    function runAction($controllerName, $action){

      $controllerClass = ucfirst($controllerName) . 'Controller';
      if(!class_exists($controllerClass)){
        throw new HttpNotFoundException();
      }
      $controller = new $controllerClass($this);
      $content = $controller->runControllerAction($action);

      $this->response->setContent($content);
  }

    public function registerRoutes(){
      $routes = [
        '/' => ['controller' => 'dashboard', 'action' =>'index'],
        '/expenses' => ['controller' => 'expenses', 'action' =>'index'],
        '/expenses/create' => ['controller' => 'expenses', 'action' =>'create'],
        '/edit' => ['controller' => 'edit', 'action' =>'index'],
        '/edit/edit' => ['controller' => 'edit', 'action' =>'edit'],
        '/edit/update' => ['controller' => 'edit', 'action' =>'update'],
      ];
      return $routes;
    }

    function render404page(){
      $this->response->setStatusCode('404', 'Not Found');
      $this->response->setContent(
<<<EOF
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404</title>
</head>
<body>

<h1>404 Page not found</h1>

</body>
</html>
EOF
      );
    }
  }
