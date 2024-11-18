<?php

namespace App\Controllers;

use App\Models\User;
use Framework\Database;
use Framework\Validator;

class UserController extends BaseController
{

    public function register()
    {
        $first = new User();
        $first->loadData();
        $db = new Database();
        $db1 = new Database();
        dump($db, $db1);
//        $user = $db->query("select * from users where id = ?", [2])->getOne();
        $user = $db->find('users', 'user12@mail.ru', 'email');
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
        $columns = implode(',', array_keys($rules));
        $values = implode(',', array_map(fn($key) => ":{$key}", array_keys($rules)));
        if ($data) {
            $db = new Database();
            $value['name'] = $data['name'];
            $value['email'] = $data['email'];
            $value['password'] = $data['password'];

            $first = new User();
            $first->loadData();
            dump($first->getAttributes());

//            $db->query("INSERT INTO users ($columns) VALUES ($values)", $value);
        } else {

        }
        response()->redirect('/register');

    }


    public function login()
    {
        return view('user/login', ['title' => 'login page']);
    }
}