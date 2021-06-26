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

        if (empty($data['pasien'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pasien dengan id ' . $id .
                'tidak ditemukan');
        }
        return view('pages/detailPasien', $data);
    }

    public function addPasien()
    {
        $data = [
            'title' => 'Form Tambah Pasien',
            'validation' => \Config\Services::validation()
        ];

        return view('pages/addPasien', $data);
    }

    public function savePasien()
    {
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
            $validation = \Config\Services::validation();
            return redirect()->to('/Pasien/addPasien')->withInput()->with('validation', $validation);
        }

        $this->pasienModel->save([
            'nama'      => $this->request->getVar('nama'),
            'alamat'    => $this->request->getVar('alamat'),
            'umur'      => $this->request->getVar('usia'),
            'gender'    => $this->request->getVar('gender'),
            'email'     => $this->request->getVar('email'),
            'password'  => $this->request->getVar('email')
        ]);

        session()->setFlashdata('pesan', 'Pasien berhasil ditambahkan.');

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
            $validation = \Config\Services::validation();
            return redirect()->to('/Pasien/editPasien/' . $idPasien)->withInput()->with('validation', $validation);
        }

        $password = 'password';
        $this->pasienModel->save([
            'idPasien' => $idPasien,
            'nama' => $this->request->getVar('nama'),
            'alamat'    => $this->request->getVar('alamat'),
            'umur' => $this->request->getVar('usia'),
            'gender' => $this->request->getVar('gender'),
            'email'     => $this->request->getVar('email'),
            'password'  => $password
        ]);

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
            if ($value >= 60 and $value <= 100) {
                $res = "normal";
            } else if ($value < 60) {
                $res = "tidak normal";
            } else if ($value > 100) {
                $res = "tidak normal";
            } else if ($value > $max) {
                $res = "tidak normal";
            }
        } else if ($param == "Saturasi Oksigen") {
            if ($value <= 94 || $value > 100) {
                $res = "tidak normal";
            }
        } else if ($param == "Temperatur") {
            if ($value < 33 || $value >= 38) {
                $res = "tidak normal";
            }
        }
        return $res;
    }

    public function riwayatPasien($id)
    {
        $tanggal = $this->request->getVar('tanggal');
        $dataPasien = $this->pasienModel->getPasien($id);

        $dataPeriksa = ($this->periksaModel->getAllHasil($id));

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

        $j = 0;
        foreach ($kumpulanhasil as $key => $res) {
            $idNode = $res['idNode'];

            $idParam = $this->memilikiModel->getParamid($idNode);
            $index = 0;

            foreach ($idParam as $id) {
                $namaParam = $this->parameterModel->getNamaParam($id['idParameter']);
                $kumpulanparam[$j][$index] = $namaParam;
                $kumpulanStatus[$j][$index] = $this->setStatus($namaParam['namaParameter'], $res['hasil' . strval($index + 1)], $dataPasien['umur']);
                $index++;
            }
            $j++;
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
                    [
                        '1' => "-"
                    ]
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

        if (empty($data['pasien'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pasien dengan id ' . $id .
                'tidak ditemukan');
        }
        return view('pages/riwayatPasien', $data);
    }

    public function tabelPeriksa()
    {
        $tabelNode1 = $this->periksaModel->tabelPeriksaNode1();
        $tabelNode2 = $this->periksaModel->tabelPeriksaNode2();

        $i = 0;
        $dataPasien1 = [];
        $dataPasien2 = [];

        $kumpulanhasil1 = [];
        $kumpulanhasil2 = [];

        $kumpulanparam1 = [];
        $kumpulanStatus1 = [];

        $kumpulanparam2 = [];
        $kumpulanStatus2 = [];

        $check1 = false;
        $check2 = false;
        foreach ($tabelNode1->getResultArray() as $res) {
            if ($res['idNode']) {
                $check1 = true;
                $kumpulanhasil1[$i] = $res;
            }
            if ($res['idPasien']) {
                $dataPasien1 = $this->pasienModel->getPasien($res['idPasien']);
            }
            $i++;
        }

        $i = 0;
        foreach ($tabelNode2->getResultArray() as $res) {
            if ($res['idNode']) {
                $check2 = true;
                $kumpulanhasil2[$i] = $res;
            }
            if ($res['idPasien']) {
                $dataPasien2 = $this->pasienModel->getPasien($res['idPasien']);
            }
            $i++;
        }

        foreach ($kumpulanhasil1 as $hasil) {
            $idNode = $hasil['idNode'];
            $idParam = $this->memilikiModel->getParamid($idNode);

            $index = 0;
            foreach ($idParam as $id) {
                $namaParam = $this->parameterModel->getNamaParam($id['idParameter']);
                $kumpulanparam1[$index] = $namaParam;
                $kumpulanStatus1[$index] = $this->setStatus($namaParam['namaParameter'], $hasil['hasil' . strval($index + 1)], $dataPasien1['umur']);
                $index++;
            }
        }

        foreach ($kumpulanhasil2 as $hasil) {
            $idNode = $hasil['idNode'];
            $idParam = $this->memilikiModel->getParamid($idNode);

            $index = 0;
            foreach ($idParam as $id) {
                $namaParam = $this->parameterModel->getNamaParam($id['idParameter']);
                $kumpulanparam2[$index] = $namaParam;
                $kumpulanStatus2[$index] = $this->setStatus($namaParam['namaParameter'], $hasil['hasil' . strval($index + 1)], $dataPasien2['umur']);
                $index++;
            }
        }

        if ($check1 == false) {
            $kumpulanhasil1 = [
                0 => [
                    'waktu' => "",
                    'hasil1' => 0,
                    'hasil2' => 0,
                    'hasil3' => 0,
                ]
            ];

            $kumpulanparam1 = [
                0 => [
                    'namaParameter' => ''
                ]
            ];

            $kumpulanStatus1 = [
                0 => "-"
            ];
        }

        if ($check2 == false) {
            $kumpulanhasil2 = [
                0 => [
                    'waktu' => "",
                    'hasil1' => 0,
                    'hasil2' => 0,
                    'hasil3' => 0,
                ]
            ];

            $kumpulanparam2 = [
                0 => [
                    'namaParameter' => ''
                ]
            ];

            $kumpulanStatus2 = [
                0 => "-"
            ];
        }

        $data = [
            'title' => 'Pemantauan Node',
            'node1' => $kumpulanhasil1,
            'node2' => $kumpulanhasil2,
            'status1' => $kumpulanStatus1,
            'status2' => $kumpulanStatus2,
            'param1' => $kumpulanparam1,
            'param2' => $kumpulanparam2,
            'pasien1' => $dataPasien1,
            'pasien2' => $dataPasien2
        ];

        return view('pages/tabelPemantauan.php', $data);
    }
}
