<?php

namespace App\Controllers;

use App\Models\memilikiModel;
use App\Models\nodeModel;
use App\Models\parameterModel;
use App\Models\pasienModel;
use App\Models\periksaModel;
use CodeIgniter\Exceptions\AlertError;
use phpDocumentor\Reflection\Types\Null_;

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

        $data = [
            'title' => 'Detail Pasien',
            'pasien' => $dataPasien
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

    public function setStatus($param, $value, $age)
    {
        $value = intval($value);
        $res = "normal";
        $age = intval($age);
        $max = 220 - $age;
        if ($param == "Detak jantung") {
            d("Masuk detak jantung");
            if ($value < 60 || $value > 100 || $value > $max) {
                $res = "tidak normal";
            }
        } else if ($param == "Saturasi Oksigen") {
            d("Masuk oksi");
            if ($value <= 94 || $value > 100) {
                $res = "tidak normal";
            }
        } else if ($param == "Temperatur") {
            d("Masuk suhu");
            if ($value >= 38) {
                $res = "tidak normal";
            }
        }
        return $res;
    }

    public function riwayatPasien($id)
    {
        $tanggal = $this->request->getVar('tanggal');
        // d($tanggal);
        $dataPasien = $this->pasienModel->getPasien($id);

        // if ($tanggal != "") {
        //     // $dataPeriksa = ($this->periksaModel->getHasilPeriksaByTime($id, $tanggal));
        //     $coba = $this->periksaModel->getWaktu($id, $tanggal);
        //     d($coba);
        //     foreach ($coba->getResultArray() as $res) {
        //         d("hasil foreach");
        //         d($res);
        //     }
        // }

        $dataPeriksa = ($this->periksaModel->getAllHasil($id));
        // d($dataPasien);

        // foreach ($dataPeriksa->getResultArray() as $a) {
        //     d($a);
        // }
        // $dataPeriksa = $this->periksaModel->getHasilPeriksa($id);
        $i = 0;
        $hasilSementara = [];
        $kumpulanhasil = [];
        $kumpulanparam = [];
        $kumpulanStatus = [];
        $check = false;
        $flagFilter = false;
        foreach ($dataPeriksa->getResultArray() as $res) {
            if ($res['idNode']) {
                $check = true;
                if ($tanggal != null) {
                    $temp = strtotime($res['waktu']);
                    $date = date('Y-m-d', $temp);
                    if ($date == $tanggal) {
                        $flagFilter = true;
                        $hasilSementara[$i] = $res;
                    }
                }
                $kumpulanhasil[$i] = $res;
            }
            $i++;
        }
        if ($flagFilter) {
            $kumpulanhasil = $hasilSementara;
        }

        if ($tanggal != "" and count($hasilSementara) == 0) {
            $flagFilter = false;
        } else {
            $flagFilter = true;
        }

        $jumlahHasil = count($kumpulanhasil);
        // d($kumpulanhasil);
        $j = 0;
        foreach ($kumpulanhasil as $key => $res) {
            // d($hasil);
            $idNode = $res['idNode'];

            $idParam = $this->memilikiModel->getParamid($idNode);
            // dd($idParam);
            $index = 0;

            foreach ($idParam as $id) {
                $namaParam = $this->parameterModel->getNamaParam($id['idParameter']);
                $kumpulanparam[$j][$index] = $namaParam;
                d($namaParam['namaParameter']);
                d($res['hasil' . strval($index + 1)]);
                // d($this->setStatus($namaParam['namaParameter'], $kumpulanhasil[$j]['hasil' . strval($index + 1)]));
                $kumpulanStatus[$j][$index] = $this->setStatus($namaParam['namaParameter'], $res['hasil' . strval($index + 1)], $dataPasien['umur']);
                // d($hasil['hasil' . strval($index + 1)]);
                // d($this->setStatus($namaParam['namaParameter'], $kumpulanhasil[$j]['hasil' . strval($index + 1)]));
                $index++;
            }
            $j++;
            // dd($kumpulanparam);
        }

        if ($check == false) {
            $jumlahHasil = 0;

            $kumpulanhasil = [
                0 => [
                    'waktu' => "",
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

            $kumpulanStatus = [
                0 => "-"
            ];
        }

        $data = [
            'title' => 'Riwayat Pasien',
            'pasien' => $dataPasien,
            'hasilPeriksa' => $kumpulanhasil,
            'parameter' => $kumpulanparam,
            'status' => $kumpulanStatus,
            'flag' => $check,
            'jumlahHasil' => $jumlahHasil,
            'flagFilter' => $flagFilter
        ];

        //jika pasien tidak ada
        if (empty($data['pasien'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pasien dengan id ' . $id .
                'tidak ditemukan');
        }
        return view('pages/riwayatPasien', $data);
    }
}
