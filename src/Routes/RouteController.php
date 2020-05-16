<?php


namespace RouteControllers\Routes;


use RouteControllers\Exceptions\ActionNotFoundException;
use RouteControllers\Exceptions\ControllerNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouteController extends BaseRoute
{
    /** @var ServerRequestInterface */
    protected $request;

    /** @var ResponseInterface */
    protected $response;

    protected $namespace = '';

    /**
     * RouteController constructor.
     *
     * Example:
     * new RouteController('GET', '/user/list') => \Routing\Controllers\UserController::actionList()
     * new RouteController('GET', '/user/detail/{id:\d+}') => \Routing\Controllers\UserController::actionDetail($id)
     * new RouteController(['GET', 'POST'], '/user') => \Routing\Controllers\UserController::actionIndex()
     * @param array|string $methods
     * @param string $path
     */
    public function __construct($method, string $path)
    {
        if (is_array($method) == false) {
            $method = [$method];
        }

        $method = array_map('strtoupper', $method);

        $this->methods = $method;
        $this->path = $path;
        $this->handler = [$this, 'proxyHandler'];
    }

    /**
     * @param string $path
     * @return array
     * @throws ActionNotFoundException
     * @throws ControllerNotFoundException
     */
    protected function parseHandler(string $path)
    {
        $parts = array_values(array_filter(explode('/', $path)));

        if (count($parts) < 1) {
            throw new ControllerNotFoundException($this->getPath(), 1);
        }

        list($controller, $action) = $parts;

        $controller = explode('-', $controller);
        $controller = implode('', array_map('ucfirst', $controller));

        $action = $action ?: 'Index';
        $action = explode('-', $action);
        $action = implode('', array_map('ucfirst', $action));

        $pattern = '/^[A-Za-z][A-Za-z0-9]+$/';
        if (preg_match($pattern, $controller) == false || preg_match($pattern, $action) == false) {
            throw new ControllerNotFoundException($this->getPath(), 2);
        }

        $className = "{$this->namespace}\\{$controller}Controller";

        if (class_exists($className) == false || is_subclass_of($className, "\\RouteControllers\\Controllers\\BaseController") == false) {
            throw new ControllerNotFoundException($this->getPath(), 3);
        }

        $class = new $className($this->request, $this->response);
        $method = 'action' . $action;

        if (method_exists($class, $method) == false) {
            throw new ActionNotFoundException($this->getPath(), $className, $method);
        }

        return [$class, $method];
    }

    /**
     * @param array $args
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param string $controllersNamespace
     * @return mixed
     * @throws ActionNotFoundException
     * @throws ControllerNotFoundException
     */
    public function proxyHandler(array $args, ServerRequestInterface $request, ResponseInterface $response, string $controllersNamespace = '')
    {
        $this->request = $request;
        $this->response = $response;
        $this->namespace = $controllersNamespace;

        list($object, $method) = $this->parseHandler($this->path);

        $data = call_user_func_array([$object, 'executeAction'], ['method'=>$method, 'arguments'=>$args]);

        return $data;
    }

    public static function get($path)
    {
        return new static([self::METHOD_GET], $path);
    }

    public static function post($path)
    {
        return new static([self::METHOD_POST], $path);
    }
}