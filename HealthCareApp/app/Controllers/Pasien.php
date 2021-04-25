<?php

namespace App\Controllers;

use App\Models\pasienModel;

class Pasien extends BaseController
{
    protected $pasienModel;

    public function __construct()
    {
        $this->pasienModel = new pasienModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar Pasien',
            'pasien' => $this->pasienModel->getPasien()
        ];
        return view('pages/listPasien', $data);
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Komik',
            'pasien' => $this->pasienModel->getPasien($id)
        ];

        return view('pages/detailPasien', $data);
    }
}
