<?php

namespace App\Middleware;

class AuthMiddleware
{
    public function handle()
    {
        if (!session()->has('user')) {
            session()->flash('error', 'Войдите или Зарегистрируйтесь');
            response()->redirect(base_url('/login'));
        }
    }
}