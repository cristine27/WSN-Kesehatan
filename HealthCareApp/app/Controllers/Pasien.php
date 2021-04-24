<?php

namespace App\Controllers;



class Pasien extends BaseController
{
    protected $pasienModel;

    public function __construct()
    {
        $this->pasienModel = new pasien();
    }

    public function index()
    {

        $data = [
            'title' => 'Daftar Pasien',
            'pasien' => $this->pasienModel->getPasien()
        ];

        return view('pages/listPasien', $data);
    }
}
