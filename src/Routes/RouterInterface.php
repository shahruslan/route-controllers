<?php


namespace RouteControllers\Routes;


interface RouterInterface
{
    public function getPath(): string;
    public function getMethods(): array;
    public function getHandler(): callable;
}