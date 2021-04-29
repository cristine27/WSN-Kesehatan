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
            'title' => 'Sign In Pasien',
            'validation' => 'true'
        ];

        return view('pages/signin');
    }

    public function validateLogin()
    {
        $email = $this->request->getVar('email');
        $pass = $this->request->getvar('password');

        if ($email == 'admin@email.com' and $pass == 'admin') {
            return view('pages/listPasien');
        } else {
            $tempEmail = $this->pasienModel->getPasienEmail($email);
            $tempPass = $this->pasienModel->getPasienPassword($pass);
            if (($tempEmail != 0) and ($tempPass != 0)) {
                return view('pages/profile');
            } else {
                $data = [
                    'title' => 'Sign In Pasien',
                    'validate' => 'false',
                    'pesan' => 'Mohon periksa kembali email dan password.'
                ];
                return view('pages/signin', $data);
            }
        }
    }
}
