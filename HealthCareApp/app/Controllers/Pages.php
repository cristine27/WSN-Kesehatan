<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $test = $db->query("SELECT * FROM N");
        dd($test);

        return view('pages/home');
    }

    public function signin()
    {
        return view('pages/signin');
    }

    public function profile()
    {
        return view('pages/profile');
    }
}
