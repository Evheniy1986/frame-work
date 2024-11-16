<?php


use App\Controllers\UserController;

app()->route->get('/', [\App\Controllers\HomeController::class, 'index']);
app()->route->get('/register', [UserController::class, 'register']);
app()->route->post('/register', [UserController::class, 'store']);
app()->route->get('/login', [UserController::class, 'login']);
app()->route->get('user/{id}/edit', [UserController::class, 'register']);
//app()->route->get('/user/(?P<id>[a-z0-9-]+)/?', [UserController::class, 'register']);


//dump(app()->route->getRoutes());