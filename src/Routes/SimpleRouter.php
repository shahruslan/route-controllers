<?php


namespace RouteControllers\Routes;


class SimpleRouter extends BaseRoute
{
    /**
     * SimpleRoute constructor.
     *
     * Example:
     * new SimpleRoute(['GET'], '/hello-world', ['Class', 'Method'])
     * or
     * new SimpleRoute(['GET', 'POST'], '/hello-world', function (){ return 'hello world'; })
     *
     * @param array $method
     * @param string $path
     * @param callable $handler
     */
    public function __construct(array $method, string $path, callable $handler)
    {
        $this->methods = $method;
        $this->path = $path;
        $this->handler = $handler;
    }

    /**
     * Create route type GET
     *
     * Example:
     * SimpleRoute::get('/hello-world', ['Class', 'Method'])
     *
     * @param string $path
     * @param callable $handler
     * @return SimpleRouter
     */
    public static function get(string $path, callable $handler)
    {
        return new static([self::METHOD_GET], $path, $handler);
    }

    /**
     * Create route type POST
     *
     * Example:
     * SimpleRoute::post('/hello-world', function (){ return 'hello world'; })
     *
     * @param string $path
     * @param callable $handler
     * @return SimpleRouter
     */
    public static function post(string $path, callable $handler)
    {
        return new static([self::METHOD_POST], $path, $handler);
    }
}