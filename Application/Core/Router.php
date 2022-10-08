<?php

namespace MVC\Core;

class Router
{
    private array $handlers;
    private const _POST   = 'post';
    private const _GET    = 'get';
    private object $notFoundHandler;

    public function get(string $path, $handler): void
    {
        $this->addHandler(self::_GET,$path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addHandler(self::_POST,$path, $handler);
    }

    public function addNotFoundHandler($handler): void
    {
        $this->notFoundHandler = $handler;
    }

    public function run()
    {
        $callback = null;
        foreach ($this->handlers as $handler){
            if ($handler['path'] === $this->path() && $this->method() === $handler['method']){
                $callback = $handler['handler'];
            }
        }
        if (!$callback){
            if (!empty($this->notFoundHandler)){
                $callback = $this->notFoundHandler;
            }
        }

        call_user_func_array($callback, [
            array_merge($_GET, $_POST)
        ]);
    }

    private function addHandler(string $method, string $path, $handler): void
    {
        $this->handlers[$method . $path] = [
            'path'      => $path,
            'method'    => $method,
            'handler'   => $handler
        ];
    }

    private function path(): string
    {
        $path       = htmlspecialchars( $_SERVER['REQUEST_URI'], ENT_QUOTES) ?? DS;
        $position   = strpos($path, '?');
        return !$position ? $path : substr($path, 0, $position);
    }

    private function method(): string
    {
        return strtolower(filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING));
    }
}