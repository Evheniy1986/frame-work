<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function about()
    {
        return view('about', ['name' => 'Igor', 'age' => 34]);
    }


    public function index()
    {
        echo view('home');
    }
}