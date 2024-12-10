<?php


use App\Controllers\UserController;

app()->route->get('/', [\App\Controllers\HomeController::class, 'index'])->middleware(['auth']);
app()->route->get('/register', [UserController::class, 'register'])->middleware('guest');
app()->route->post('/register', [UserController::class, 'store'])->middleware('guest');
app()->route->get('/login', [UserController::class, 'login'])->middleware('guest');
app()->route->get('user/{id}/edit', [UserController::class, 'register']);
//app()->route->get('/user/(?P<id>[a-z0-9-]+)/?', [UserController::class, 'register']);


//dump(app()->route->getRoutes());