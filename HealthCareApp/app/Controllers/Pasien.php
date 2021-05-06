<?php

namespace App\Controllers;

use App\Models\memilikiModel;
use App\Models\nodeModel;
use App\Models\parameterModel;
use App\Models\pasienModel;
use App\Models\periksaModel;

use function PHPSTORM_META\map;

class Pasien extends BaseController
{
    protected $pasienModel;
    protected $periksaModel;
    protected $nodeModel;
    protected $memilikiModel;
    protected $parameterModel;

    public function __construct()
    {
        $this->pasienModel = new pasienModel();
        $this->periksaModel = new periksaModel();
        $this->nodeModel = new nodeModel();
        $this->memilikiModel = new memilikiModel();
        $this->parameterModel = new parameterModel();
    }

    public function index()
    {
        // dd($this->request->getVar('page_pasien'));
        $currentPage = $this->request->getVar('page_pasien') ? $this->request->getVar('page_pasien') : 1;

        $pencarian = $this->request->getVar('pencarian');
        if ($pencarian) {
            $pasien = $this->pasienModel->searchPasien($pencarian);
        } else {
            $pasien = $this->pasienModel;
        }

        $data = [
            'title' => 'Daftar Pasien',
            'pasien' => $pasien->getPaginate(6),
            'pager' => $pasien->getPager(),
            'currentPage' => $currentPage
        ];
        return view('pages/listPasien', $data);
    }

    public function detail($id)
    {
        $dataPasien = $this->pasienModel->getPasien($id);
        // d($dataPasien);
        $dataPeriksa = $this->periksaModel->getHasilPeriksa($id);
        $i = 0;
        $kumpulanhasil = [];
        $kumpulanparam = [];
        $check = false;
        foreach ($dataPeriksa->getResultArray() as $res) {
            // d($res);
            if ($res['idNode']) {
                $check = true;
                $kumpulanhasil[$i] = $res;
            }
        }

        foreach ($kumpulanhasil as $hasil) {
            d($hasil);
            $idNode = $hasil['idNode'];
            // d($idNode);
            $idParam = $this->memilikiModel->getParamid($idNode);

            $index = 0;
            foreach ($idParam as $id) {
                $kumpulanparam[$index] = $this->parameterModel->getNamaParam($id['idParameter']);
                $index++;
            }
        }

        if (!$check) {
            $kumpulanhasil = [
                0 => [
                    'hasil1' => 0,
                    'hasil2' => 0,
                    'hasil3' => 0,
                ]
            ];

            $kumpulanparam = [
                0 => [
                    'namaParameter' => ''
                ]
            ];
        }

        $data = [
            'title' => 'Detail Komik',
            'pasien' => $dataPasien,
            'hasilPeriksa' => $kumpulanhasil,
            'parameter' => $kumpulanparam,
            'flag' => $check
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
        // session();
        $data = [
            'title' => 'Form Tambah Pasien',
            'validation' => \Config\Services::validation()
        ];

        return view('pages/addPasien', $data);
    }

    public function savePasien()
    {

        //validasi input, ditarget berdasasrkan name form
        if (!$this->validate([
            'nama' => [
                'rules' => 'required|alpha_space',
                'errors' => [
                    'required' => '{field} pasien harus diisi.',
                    'alpha_space' => '{field} pasien hanya diisi dapat diisi dengan huruf.'
                ]
            ],
            'usia' => [
                'rules' => 'required|is_natural_no_zero|less_than_equal_to[100]|numeric',
                'errors' => [
                    'required' => '{field} pasien harus diisi.',
                    'is_natural_no_zero' => '{field} pasien tidak boleh 0.',
                    'numeric' => 'harus diisi dengan angka.'
                ]
            ],
            'gender' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ],
            'email' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            //pesan error
            $validation = \Config\Services::validation();
            // dd($validation);
            //mengirimkan validasi dan pesan error ke halaman add pasien
            return redirect()->to('/Pasien/addPasien')->withInput()->with('validation', $validation);
        }

        $password = 'password';

        $this->pasienModel->save([
            'nama'      => $this->request->getVar('nama'),
            'alamat'    => $this->request->getVar('alamat'),
            'umur'      => $this->request->getVar('usia'),
            'gender'    => $this->request->getVar('gender'),
            'email'     => $this->request->getVar('email'),
            'password'  => $password
        ]);

        //buat flash data notif save berhasil
        session()->setFlashdata('pesan', 'Pasien berhasil ditambahkan.');

        return redirect()->to('/Pasien');
    }

    public function deletePasien($idPasien)
    {
        //cara konvenstional bahaya karena bisa delete lewat url method->get 
        // harus di tambahkan http method spoofing
        $this->pasienModel->delete($idPasien);
        session()->setFlashdata('pesan', 'Pasien berhasil dihapus.');

        return redirect()->to('/Pasien');
    }

    public function editPasien($idPasien)
    {
        $data = [
            'title' => 'Form Ubah Pasien',
            'validation' => \Config\Services::validation(),
            'pasien' => $this->pasienModel->getPasien($idPasien)
        ];

        return view('pages/editPasien', $data);
    }

    public function updatePasien($idPasien)
    {
        //validasi input, ditarget berdasasrkan name form
        if (!$this->validate([
            'nama' => [
                'rules' => 'required|alpha_space',
                'errors' => [
                    'required' => '{field} pasien harus diisi',
                    'alpha_space' => '{field} pasien hanya diisi dapat diisi dengan huruf'
                ]
            ],
            'usia' => [
                'rules' => 'required|is_natural_no_zero|less_than_equal_to[100]|numeric',
                'errors' => [
                    'required' => '{field} pasien harus diisi',
                    'is_natural_no_zero' => '{field} pasien tidak boleh 0',
                    'numeric' => 'harus diisi dengan angka'
                ]
            ],
            'gender' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi'
                ]
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ],
            'email' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            //pesan error
            $validation = \Config\Services::validation();
            // dd($validation);
            //mengirimkan validasi dan pesan error ke halaman add pasien
            return redirect()->to('/Pasien/editPasien/' . $idPasien)->withInput()->with('validation', $validation);
        }

        $password = 'password';
        // dd($this->request->getVar());
        $this->pasienModel->save([
            'idPasien' => $idPasien,
            'nama' => $this->request->getVar('nama'),
            'alamat'    => $this->request->getVar('alamat'),
            'umur' => $this->request->getVar('usia'),
            'gender' => $this->request->getVar('gender'),
            'email'     => $this->request->getVar('email'),
            'password'  => $password
        ]);

        //buat flash data notif save berhasil
        session()->setFlashdata('pesan', 'Pasien berhasil diubah.');

        return redirect()->to('/Pasien');
    }
}
