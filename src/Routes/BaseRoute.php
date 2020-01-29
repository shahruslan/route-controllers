<?php


namespace RouteControllers\Routes;


abstract class BaseRoute implements RouterInterface
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_HEAD = 'HEAD';

    /** @var array */
    protected $methods;
    /** @var string */
    protected $path;
    /** @var callable */
    protected $handler;

    public function isValid(): bool
    {
        $methodList = [
            self::METHOD_GET,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_PATCH,
            self::METHOD_DELETE,
            self::METHOD_HEAD
        ];

        foreach ($this->methods as $method) {
            if (in_array($method, $methodList) == false) {
                return false;
            }
        }


        if (is_callable($this->handler) == false) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return callable
     */
    public function getHandler(): callable
    {
        return $this->handler;
    }
}