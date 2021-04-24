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
}
