<?php

namespace Framework;

class Session
{

    public function __construct()
    {
        session_start();
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public function remove($key)
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public function flash($key, $message = null)
    {
        if ($message) {
            $_SESSION['flash'][$key] = $message;
        } else {
            $flashMessage = $_SESSION['flash'][$key] ?? null;
            if ($flashMessage) {
                unset($_SESSION['flash'][$key]);
            }
            return $flashMessage;
        }
    }

}