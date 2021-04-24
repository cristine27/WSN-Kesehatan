<?php

namespace App\Controllers;

use App\Models\pasienModel;

class Pages extends BaseController
{
    protected $pasienModel;

    public function __construct()
    {
        $this->pasienModel = new pasienModel();
    }

    public function index()
    {
        // cara konek tanpa model
        dd($this->pasienModel->getPasien());
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
        $data = [
            'title' => 'Daftar Pasien',
            'pasien' => $this->pasienModel->getPasien()
        ];
        return view('pages/listPasien', $data);
    }
}
