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
            'pesan' => ''
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
                $kumpulanparam = 0;
                dd($idParam);
                foreach ($idParam as $id) {
                    d($this->parameterModel->getNamaParam($id));
                }

                $data = [
                    'title' => 'Profile Pasien',
                    'dataPasien' => $dataPasien,
                    'hasilPeriksa' => $dataPeriksaArr,
                    'parameter' => $kumpulanparam,
                    'idParam' => $idParam
                ];

                return view('pages/homePasien', $data);
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
}
