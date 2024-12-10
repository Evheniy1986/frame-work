<?php

namespace App\Middleware;

class GuestMiddleware
{
    public function handle()
    {
        if (session()->get('user') && isset(session()->get('user')['id'])) {
            session()->flash('success', 'Вы уже авторизованы');
            response()->redirect(base_url('/'));
        }
    }
}