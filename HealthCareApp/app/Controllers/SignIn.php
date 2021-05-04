<?php

namespace App\Controllers;

// cara kedua tanpa namespace

use App\Models\memilikiModel;
use App\Models\nodeModel;
use App\Models\parameterModel;
use App\Models\pasienModel;
use App\Models\periksaModel;

class SignIn extends BaseController
{
    protected $pasienModel;
    protected $periksaModel;
    protected $nodeModel;
    protected $memilikiModel;
    protected $parameterModel;
    protected $hasilPeriksa;
    protected $paramter;

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
        $data = [
            'title' => 'Sign In Pasien',
            'validate' => 'true',
            'pesan' => '',
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

            if (($dataPasien['email'] == $email) and ($dataPasien['password'] == $pass)) {
                $idPasien = $dataPasien['idPasien'];
                $dataPeriksa = $this->periksaModel->getHasilPeriksa($idPasien);
                $dataPeriksaArr = 0;
                foreach ($dataPeriksa->getResultArray() as $res) {
                    $dataPeriksaArr = $res;
                }

                $idNode = $dataPeriksaArr['idNode'];
                $idParam = $this->memilikiModel->getParamid($idNode);
                $kumpulanparam = [];
                // dd($idParam);
                $index = 0;
                foreach ($idParam as $id) {
                    // d("masuk");
                    $kumpulanparam[$index] = $this->parameterModel->getNamaParam($id['idParameter']);
                    $index++;
                }

                $data = [
                    'title' => 'Profile Pasien',
                    'dataPasien' => $dataPasien
                    // 'idParam' => $idParam
                ];
                session()->set('pasien', $dataPasien);
                return redirect()->to('../Home');
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

    public function getHasilPantau($idPasien)
    {
        // $idPasien = $dataPasien['idPasien'];
        $dataPeriksa = $this->periksaModel->getHasilPeriksa($idPasien);
        // d($dataPeriksa);
        $dataPeriksaArr = 0;
        foreach ($dataPeriksa->getResultArray() as $res) {
            $dataPeriksaArr = $res;
        }
        $this->hasilPeriksa = $dataPeriksaArr;
        $idNode = $dataPeriksaArr['idNode'];
        $idParam = $this->memilikiModel->getParamid($idNode);
        $kumpulanparam = [];
        // dd($idParam);
        $index = 0;
        foreach ($idParam as $id) {
            // d("masuk");
            $kumpulanparam[$index] = $this->parameterModel->getNamaParam($id['idParameter']);
            $index++;
        }
        $this->paramter = $kumpulanparam;
        $data = [
            'title' => 'Pemeriksaan Pasien',
            'hasilPeriksa' => $dataPeriksaArr,
            'parameter' => $kumpulanparam
            // 'idParam' => $idParam
        ];

        return view('pages/pemantauanPasien', $data);
    }

    public function getPasienProfile($idPasien)
    {
        $data = [
            'title' => 'Profile Pasien',
            'dataPasien' => $this->pasienModel->getPasien($idPasien)
        ];

        return view('pages/profile', $data);
    }
}
