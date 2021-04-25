<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
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

    public function addPasien()
    {
        return view('pages/addPasien');
    }

    public function listPasien()
    {
        return view('pages/listPasien');
    }
}
