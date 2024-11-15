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
    public Session $session;

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
        $this->session = new Session();
        $this->generateCsrfToken();
    }

    public function run()
    {
       echo $this->route->dispatch();
    }

    public function generateCsrfToken()
    {
        if (!$this->session->has('csrf_token')) {
            $this->session->set('csrf_token', md5(uniqid(mt_rand(),true)));
        }
    }


}