<?php

namespace App\Controllers;

use App\Models\User;
use Framework\Database;
use Framework\Validator;

class UserController extends BaseController
{

    public function register()
    {
        $db = db();
        $user = User::query()->where('is_admin', '=', 0)->get();
//        dump($db, $user);
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
            $user = new User();

            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
//            dd($user, $data);

            $user->save();


        } else {
            response()->redirect('/register')->withErrors($data);
        }
        response()->redirect('/register');

    }


    public function login()
    {
        return view('user/login', ['title' => 'login page']);
    }
}