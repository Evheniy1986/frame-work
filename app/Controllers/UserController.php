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

        $data = request()->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmation',
        ]);

        dump($data);

//        if (isset($data['errors'])) {
//            dump($data['errors']);
//        } else {
//            echo "Validation passed!";
//        }

    }


    public function login()
    {
        return view('user/login', ['title' => 'login page']);
    }
}