<?php


namespace RouteControllers\Exceptions;


class ControllerNotFoundException extends RouteException
{
    protected $path;

    public function __construct($path, $code = 0, \Throwable $previous = null)
    {
        $this->path = $path;
        $message = "Не найден контроллер для роута $path";

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
}