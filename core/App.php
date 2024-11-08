<?php

namespace Framework;


class App
{

    private string $uri;
    public Request $request;
    public Route $route;
    public Response $response;

    public View $view;

    public ErrorHandler $errorHandler;

    public static App $app;

    public function __construct()
    {
        self::$app = $this;
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->request = new Request($this->uri);
        $this->response = new Response();
        $this->route = new Route($this->request, $this->response);
        $this->view = new View("main");
        $this->errorHandler = new ErrorHandler(true);
    }

    public function run()
    {
       echo $this->route->dispatch();
    }


}