<?php


namespace RouteControllers\Exceptions;


class ActionNotFoundException extends RouteException
{
    protected $path;
    protected $controller;
    protected $action;

    public function __construct($path, $controller, $action, $code = 0, \Throwable $previous = null)
    {
        $message = "Не найден метод $action для роута $path";

        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }
}