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

        //jika pasien tidak ada
        if(empty($data['pasien'])){
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pasien dengan id ' . $id . 
            'tidak ditemukan');
        }

        return view('pages/detailPasien', $data);
    }

    public function addPasien()
    {
        $data = [
            'title' => 'Form Tambah Pasien',
            'pasien' => 
        ];

        return view('pages/addPasien', $data);
    }

    public function savePasien()
    {
        $this->pasienModel->save([
            'nama' => $this->request->getVar('nama'),
            'umur' => $this->request->getVar('umur'),
            'jenis kelamin' => $this->request->getVar('jenis kelamin')
        ]);

        //buat flash data notif save berhasil
        session()->setFlashdata('pesan', 'Pasien berhasil ditambahkan');
        
        return redirect()->to('/Pasien');
    }
}
