<?php

namespace Framework;

class Request
{
    public string $uri;

    public function __construct($uri)
    {
        $this->uri = trim(urldecode($uri), '/');

    }

    public function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function getPath()
    {
        if ($this->uri) {
            $path = parse_url($this->uri)['path'];
            return trim($path, '/');
        }
        return "";
    }

    public function isGet(): bool
    {
        return $this->getMethod() == 'GET';
    }

    public function isPost(): bool
    {
        return $this->getMethod() == 'POST';
    }

    public function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public function get($name, $default = null)
    {
        return $_GET[$name] ?? $default;
    }

    public function post($name, $default = null)
    {
        return $_POST[$name] ?? $default;
    }

    public function input($fieldName, $default = null)
    {
        return $this->post($fieldName) ?? $this->get($fieldName) ?? $default;
    }

    public function all()
    {
        $data = [];
        $requestData = $this->isPost() ? $_POST : $_GET;
        foreach ($requestData as $k => $v) {
            if (is_string($v)) {
                $v = trim($v);
            }
            $data[$k] = $v;
        }
        return $data;
    }

    public function validatedData($class)
    {
        $data = new $class;
        $data->loadData();
        return $data->getAttributes();
    }

    public function validate(array $rules)
    {
        $data = $this->all();

            $validator = new Validator();

            if ($validator->validate($data, $rules)) {
                session()->remove('form_errors');
                session()->remove('form_data');
                session()->flash('success', 'success Validation');
                return $data;
            }
            session()->flash('error', 'Error Validation');
            session()->set('form_errors', $validator->getErrors());
            session()->set('form_data', $data);
            return [];
    }


    public function errors()
    {
        $validator = new Validator();

        return $validator->getErrors();
    }


}