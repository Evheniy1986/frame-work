<?php

function app(): \Framework\App
{
   return \Framework\App::$app;
}

function request(): \Framework\Request
{
   return app()->request;
}

function response(): \Framework\Response
{
    return app()->response;
}

function view($view = '', $data = [], $layout = ''): string|\Framework\View
{
    if ($view) {
        return app()->view->render($view, $data, $layout);
    }
    return app()->view;
}

function content()
{
    return view()->getContent();
}

function abort($error = '', $code = 404)
{
    response()->setResponseCode($code);
    echo view("/errors/{$code}", ['error' => $error], false);
    die;
}

function base_url($path = ''): string
{
    return PATH . $path;
}