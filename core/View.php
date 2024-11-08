<?php

namespace Framework;

class View
{
    public string $layout;
    public string $content = '';

    public function __construct($layout)
    {
        $this->layout = $layout;
    }

    public function render($view, $data = [], $layout = ''): string
    {
        extract($data);
        $view_file = APP_PATH . "/views/{$view}.php";

        if (is_file($view_file)) {
            ob_start();
            require $view_file;
            $this->content = ob_get_clean();
        } else {
            abort("Not found view {$view_file}", 404);
        }

        if (false === $layout) {
            return $this->content;
        }

        $layout_file_name = $layout ?: $this->layout;
        $layout_file = APP_PATH . "/views/layouts/{$layout_file_name}.php";
        if (is_file($layout_file)) {
            ob_start();
            require_once $layout_file;
            return ob_get_clean();
        } else {
            abort("Not found layout {$layout_file}", 500);
        }
        return '';
    }

    public function getContent(): string
    {
        return $this->content;
    }
}