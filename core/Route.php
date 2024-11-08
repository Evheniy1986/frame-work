<?php

namespace Framework;

class Route
{

    protected array $routes = [];
    public array $route_params = [];

    public function __construct(
        protected Request  $request,
        protected Response $response
    )
    {

    }

    public function add($path, $function, $method): self
    {
        $path = trim($path, '/');
        if (is_array($method)) {
            $method = array_map('strtoupper', $method);
        } else {
            $method = [strtoupper($method)];
        }
        $this->routes[] = [
            'path' => "/$path",
            'function' => $function,
            'method' => $method,
            'middleware' => null
        ];
        return $this;
    }

    public function get($path, $function): self
    {
        return $this->add($path, $function, 'GET');
    }

    public function post($path, $function): self
    {
        return $this->add($path, $function, 'POST');
    }


    public function dispatch()
    {
        $path = $this->request->getPath();
        $route = $this->machRoute($path);
        if ($route === false) {
          abort('Route not found', 404);
        }
        if (is_array($route['function'])) {

            $route['function'][0] = new $route['function'][0];
        }
        return call_user_func($route['function']);
    }

    protected function machRoute($path)
    {
        foreach ($this->routes as $route) {
            if (preg_match("#^{$route['path']}$#", "/{$path}", $matches)
                &&
                in_array($this->request->getMethod(), $route['method'])) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $this->route_params[$k] = $v;
                    }
                }
                return $route;
            }
        }
        return false;
    }


    public function getRoutes(): array
    {
        return $this->routes;
    }
}