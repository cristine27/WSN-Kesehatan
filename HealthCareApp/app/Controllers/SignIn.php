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
            'validate' => 'true',
            'pesan' => ''
        ];

        return view('pages/signin', $data);
    }

    public function validateLogin()
    {
        $email = $this->request->getVar('email');
        $pass = $this->request->getvar('password');

        if ($email == 'admin@email.com' and $pass == 'admin') {
            return view('pages/listPasien');
        } else {
            $temp = $this->pasienModel->getPasienbyEmail($email);
            if (($temp['email'] == $email) and ($temp['password'] == $pass)) {
                $data = [
                    'title' => 'Profile Pasien',
                    'dataPasien' => $temp
                ];

                return view('pages/profile', $data);
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
