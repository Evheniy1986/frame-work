<?php


use App\Controllers\UserController;

app()->route->get('/', [\App\Controllers\HomeController::class, 'index']);
app()->route->get('/register', [UserController::class, 'register']);
app()->route->post('/register', [UserController::class, 'store']);
app()->route->get('/login', [UserController::class, 'login']);


//dump(app()->route->getRoutes());