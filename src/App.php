<?php


namespace RouteControllers;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RouteControllers\Routes\RouterInterface;
use Slim\Factory\AppFactory;

class App
{
    /** @var RouterInterface[] */
    private $routes = [];

    private $basePath;
    private $controllersNamespace;

    public function __construct($basePath = '', $namespace = '')
    {
        $this->basePath = $basePath;
        $this->controllersNamespace = $namespace;
    }

    public function setRoutes(array $routes)
    {
        foreach ($routes as $route) {
            //todo isValid()
            $this->routes[] = $route;
        }
    }

    public function run()
    {
        $app = AppFactory::create();
        $app->setBasePath($this->basePath);

        foreach ($this->routes as $route) {

            $methods = $route->getMethods();
            $path = $route->getPath();
            $controllersNamespace = $this->controllersNamespace;

            $handler = function (ServerRequestInterface $request, ResponseInterface $response, array $args) use ($route, $controllersNamespace) {
                $data = call_user_func_array($route->getHandler(), [$args, $request, $response, $controllersNamespace]);

                if (($data instanceof ResponseInterface) == false) {
                    $response->getBody()->write($data);
                }

                return $response;
            };

            $app->map($methods, $path, $handler);
        }

        $app->run();

    }
}