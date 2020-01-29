<?php


namespace RouteControllers\Controllers;


class JsonController extends BaseController
{
    protected function formatResponse($data)
    {
        $this->response = $this->response->withHeader('Content-Type', 'application/json; charset=UTF-8');
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}