<?php

namespace App\Controllers;

use App\Models\pasienModel;

use function PHPSTORM_META\map;

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
        if (empty($data['pasien'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pasien dengan id ' . $id .
                'tidak ditemukan');
        }

        return view('pages/detailPasien', $data);
    }

    public function addPasien()
    {
        // session(); pindah ke basecontroller
        $data = [
            'title' => 'Form Tambah Pasien',
            'validation' => \Config\Services::validation()
        ];

        return view('pages/addPasien', $data);
    }

    public function savePasien()
    {

        //validasi input, ditarget berdasasrkan name form
        // if (!$this->validate([
        //     'nama' => [
        //         'rules' => 'required|alpha_space',
        //         'errors' => [
        //             'required' => '{field} pasien harus diisi',
        //             'alpha_space' => '{field} pasien hanya diisi dapat diisi dengan huruf'
        //         ]
        //     ],
        //     'usia' => [
        //         'rules' => 'required|is_natural_no_zero|less_than_equal_to[100]|numeric',
        //         'errors' => [
        //             'required' => '{field} pasien harus diisi',
        //             'is_natural_no_zero' => '{field} pasien tidak boleh 0',
        //             'numeric' => 'harus diisi dengan angka'
        //         ]
        //     ],
        //     'jenisKelamin' => [
        //         'rules' => 'required',
        //         'errors' => '{field} jenis kelamin harus diisi'
        //     ]
        // ])) {
        //     //pesan error
        //     $validation = \Config\Services::validation();
        //     //mengirimkan validasi dan pesan error ke halaman add pasien
        //     return redirect()->to('/Pasien/addPasien')->withInput()->with('validation', $validation);
        // }
        $temp = "";
        if ($this->request->getVar('gender') == 'Pria') {
            $temp = "P";
        } else {
            $temp = "W";
        }

        $this->pasienModel->save([
            'nama' => $this->request->getVar('nama'),
            'umur' => $this->request->getVar('usia'),
            'jenis kelamin' => $temp
        ]);

        //buat flash data notif save berhasil
        session()->setFlashdata('pesan', 'Pasien berhasil ditambahkan');

        return redirect()->to('/Pasien');
    }
}
