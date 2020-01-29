<?php


namespace RouteControllers\Controllers;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BaseController
{
    /** @var ServerRequestInterface  */
    protected $request;

    /** @var ResponseInterface */
    protected $response;

    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    protected function formatResponse($data)
    {
        return (string)$data;
    }

    final public function executeAction($method, $arguments): ResponseInterface
    {
        $data = call_user_func_array([$this, $method], $arguments);

        $body = $this->formatResponse($data);
        $this->response->getBody()->write($body);

        return $this->response;
    }

    protected function render($filePath, /** @noinspection PhpUnusedParameterInspection */ array $fields)
    {
        ob_start();

        /** @noinspection PhpIncludeInspection */
        include $_SERVER['DOCUMENT_ROOT'] . $filePath;

        return ob_get_clean();
    }
}