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

function validator(): \Framework\Validator
{
    return new \Framework\Validator();
}

function session(): \Framework\Session
{
    return app()->session;
}

function view($view = '', $data = [], $layout = ''): string|\Framework\View
{
    if ($view) {
        return app()->view->render($view, $data, $layout);
    }
    return app()->view;
}

/**
 * @return string|void
 */
function getFlash()
{
    if (!empty($_SESSION['flash'])) {
        foreach ($_SESSION['flash'] as $key => $value) {
            return view()->renderPartials("incs/flash/$key", [$key => session()->flash($key)]);
        }
    }
}

function get_errors($field)
{
    $output = '';
    $errors = session()->get('form_errors');

    if (isset($errors[$field])) {
        $output .= '<div class="invalid-feedback d-block"><ul class="list-unstyled">';
        foreach ($errors[$field] as $error) {
            $output .= '<li>' . h($error) .'</li>';
        }
        $output .= '</ul></div>';
    }
    return $output;
}

function get_class_validation($field)
{
    $errors = session()->get('form_errors');
    if (empty(session()->get('form_data'))) {
        return '';
    }
    return isset($errors[$field]) ? 'is-invalid' : 'is-valid';
}


function old($field)
{
    return isset(session()->get('form_data')[$field]) ? h(session()->get('form_data')[$field]) : '';
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

function h($str): string
{
    return htmlspecialchars($str, ENT_QUOTES);
}

function csrf_token_field()
{
    return '<input type="hidden" name="csrf_token" value="' . session()->get('csrf_token') .'">';
}

function meta_csrf_token()
{
    return '<meta name="csrf-token" content="'. session()->get('csrf_token') .'">';
}