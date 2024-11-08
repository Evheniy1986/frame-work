<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends BaseController
{

    public function register()
    {
        return view('user/register', ['title' => 'Register page']);
    }

    public function store()
    {
        $model = new User();
//        $model->loadData();

        dump($_POST);

        dump($model->loadData());
        dump($model->attributes);
    }

    public function login()
    {
        return view('user/login', ['title' => 'login page']);
    }
}