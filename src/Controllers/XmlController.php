<?php


namespace RouteControllers\Controllers;


class XmlController extends BaseController
{
    protected function formatResponse($data)
    {
        $this->response = $this->response->withHeader('Content-Type', 'text/xml; charset=UTF-8');
        return $data;
    }
}