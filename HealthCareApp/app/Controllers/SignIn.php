<?php

namespace App\Controllers;

// cara kedua tanpa namespace

use App\Models\pasienModel;

class SignIn extends BaseController
{
    protected $pasienModel;

    public function __construct()
    {
        $this->pasienModel = new pasienModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Sign In Pasien'
        ];

        return view('pages/signin');
    }

    public function validateLogin()
    {
        $email = $this->request->getVar('email');
        $pass = $this->request->getvar('password');
        d($email);
        d($pass);
    }
}
