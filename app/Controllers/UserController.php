<?php

namespace App\Controllers;

use App\Models\User;
use Framework\Validator;

class UserController extends BaseController
{

    public function register()
    {

        return view('user/register', ['title' => 'Register page']);
    }

    public function store()
    {

        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ];
        $data = request()->validate($rules);

        if ($data) {

        } else {

        }
        response()->redirect('/register');

    }


    public function login()
    {
        return view('user/login', ['title' => 'login page']);
    }
}