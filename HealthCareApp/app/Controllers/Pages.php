<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Home'
        ];
        return view('pages/home', $data);
    }

    public function signin()
    {
        $data = [
            'title' => 'Sign In'
        ];
        return view('pages/signin', $data);
    }

    public function profile()
    {
        $data = [
            'title' => 'Profile'
        ];
        return view('pages/profile', $data);
    }

    public function addPasien()
    {
        $data = [
            'title' => 'tambah pasien'
        ];
        return view('pages/addPasien', $data);
    }
}
