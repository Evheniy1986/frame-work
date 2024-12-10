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
        $path = preg_replace('/{([a-zA-Z0-9_]+)}/', '(?P<\1>[a-zA-Z0-9_-]+)', $path);
        if (is_array($method)) {
            $method = array_map('strtoupper', $method);
        } else {
            $method = [strtoupper($method)];
        }
        $this->routes[] = [
            'path' => "/$path",
            'function' => $function,
            'method' => $method,
            'middleware' => [],
            'needCsrfToken' => true
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
                if (request()->isPost()) {
                    if (!$this->checkCsrfToken() && $route['needCsrfToken']) {
                        if (request()->isAjax()) {
                            echo json_encode([
                                'status' => 'error',
                                'message' => 'Security error'
                            ]);
                            die;
                        } else {
                            abort('Page expired', 419);
                        }
                    }
                }
                if (!empty($route['middleware']) && is_array($route['middleware'])) {
                    foreach ($route['middleware'] as $item) {
                        if (!isset(MIDDLEWARE[$item])) {
                            abort("Middleware '{$item}' не найден в MIDDLEWARE", 500);
                        }

                        $middlewareClass = MIDDLEWARE[$item];

                        if (class_exists($middlewareClass)) {
                            $middlewareInstance = new $middlewareClass();
                            if (method_exists($middlewareInstance, 'handle')) {
                                $middlewareInstance->handle();
                            } else {
                                abort("Метод handle отсутствует в классе middleware '{$middlewareClass}'", 500);
                            }
                        } else {
                            abort("Класс middleware '{$middlewareClass}' не найден", 500);
                        }
                    }
                }
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

    public function middleware($middleware)
    {
        if (is_string($middleware)) {
            $middleware = [$middleware];
        }

        if (!empty($this->routes)) {
            $lastRouteIndex = array_key_last($this->routes);

            if ($lastRouteIndex !== null) {
                $this->routes[$lastRouteIndex]['middleware'] = $middleware;
            }
        }

        return $this;
    }

    protected function checkCsrfToken(): bool
    {
        return $this->request->post('csrf_token') && ($this->request->post('csrf_token') == session()->get('csrf_token'));
    }

    public function withoutCsrfToken()
    {
        $lastRouteIndex = array_key_last($this->routes);
        if ($lastRouteIndex >= 0) {
            $this->routes[$lastRouteIndex]['needCsrfToken'] = false;
        }
        return $this;
    }


    public function getRoutes(): array
    {
        return $this->routes;
    }
}