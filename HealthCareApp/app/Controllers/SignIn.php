<?php

namespace App\Controllers;

// cara kedua tanpa namespace

use App\Models\pasienModel;
use App\Models\periksaModel;

class SignIn extends BaseController
{
    protected $pasienModel;
    protected $periksaModel;

    public function __construct()
    {
        $this->pasienModel = new pasienModel();
        $this->periksaModel = new periksaModel();
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
            $dataPasien = $this->pasienModel->getPasienbyEmail($email);

            if (($temp['email'] == $email) and ($temp['password'] == $pass)) {
                $idPasien = $dataPasien['idPasien'];
                $dataPeriksa = $this->periksaModel->getHasilPeriksa($idPasien);
                $data = [
                    'title' => 'Profile Pasien',
                    'dataPasien' => $dataPasien,
                    'hasilPeriksa' => $dataPeriksa
                ];

                return view('pages/homePasien', $data);
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
