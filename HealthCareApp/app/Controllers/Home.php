<?php

namespace App\Controllers;

// cara kedua tanpa namespace

use App\Models\memilikiModel;
use App\Models\nodeModel;
use App\Models\parameterModel;
use App\Models\pasienModel;
use App\Models\periksaModel;

class Home extends BaseController
{
	protected $pasienModel;
	protected $periksaModel;
	protected $nodeModel;
	protected $memilikiModel;
	protected $parameterModel;

	protected $dataPasien;
	protected $hasilPeriksa;
	protected $parameter;

	public function __construct()
	{
		$this->pasienModel = new pasienModel();
		$this->periksaModel = new periksaModel();
		$this->nodeModel = new nodeModel();
		$this->memilikiModel = new memilikiModel();
		$this->parameterModel = new parameterModel();
		// $this->dataPasien = session()->getFlashData('pasien');
	}

	public function index()
	{
		// $this->dataPasien = $this->pasienModel->getPasien($idPasien);
		// dd($this->dataPasien);
		// dd(isset($_SESSION['pasien']));
		$this->dataPasien = session()->get('pasien');
		$check = false;
		if ($this->dataPasien['password'] == "password") {
			$check = true;
		}
		$data = [
			'title' => 'Home Pasien',
			'dataPasien' => $this->dataPasien,
			'hasilPeriksa' => $this->hasilPeriksa,
			'parameter' => $this->parameter,
			'flag' => $check
		];
		d($this->dataPasien);
		return view('pages/homePasien', $data);
	}

	public function getHasilPantau()
	{
		// $idPasien = $dataPasien['idPasien'];
		// $this->dataPasien = $this->pasienModel->getPasien($idPasien);
		$this->dataPasien = session()->get('pasien');
		$dataPeriksa = $this->periksaModel->getHasilPeriksa($this->dataPasien['idPasien']);
		// d($dataPeriksa);
		$i = 0;
		$kumpulanhasil = [];
		$kumpulanparam = [];
		$kumpulanStatus = [];
		$check = false;


		$dataPeriksaArr = 0;
		foreach ($dataPeriksa->getResultArray() as $res) {
			if ($res['idNode']) {
				$check = true;
				$kumpulanhasil[$i] = $res;
			}
			$i++;
		}

		foreach ($kumpulanhasil as $hasil) {
			// d($hasil);
			$idNode = $hasil['idNode'];
			// d($idNode);
			$idParam = $this->memilikiModel->getParamid($idNode);

			$index = 0;
			foreach ($idParam as $id) {
				$namaParam = $this->parameterModel->getNamaParam($id['idParameter']);
				$kumpulanparam[$index] = $namaParam;
				$kumpulanStatus[$index] = $this->setStatus($namaParam, $hasil['hasil' . strval($index + 1)]);
				// d($hasil['hasil' . strval($index + 1)]);
				// d($this->setStatus($namaParam, $hasil['hasil' . strval($index + 1)]));
				$index++;
			}
		}
		// $this->hasilPeriksa = $dataPeriksaArr;
		// $idNode = $dataPeriksaArr['idNode'];
		// $idParam = $this->memilikiModel->getParamid($idNode);
		// $kumpulanparam = [];
		// // dd($idParam);
		// $index = 0;
		// foreach ($idParam as $id) {
		// 	// d("masuk");
		// 	$kumpulanparam[$index] = $this->parameterModel->getNamaParam($id['idParameter']);
		// 	$index++;
		// }
		// dd($kumpulanparam);

		if ($check == false) {
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

			$kumpulanStatus = [
				0 => "-"
			];
		}
		$this->parameter = $kumpulanparam;
		$this->hasilPeriksa = $kumpulanhasil;
		$data = [
			'title' => 'Pemeriksaan Pasien',
			'hasilPeriksa' => $kumpulanhasil,
			'parameter' => $kumpulanparam,
			'status' => $kumpulanStatus,
			'flag' => $check
		];

		return view('pages/pemantauanPasien', $data);
	}

	public function getPasienProfile()
	{
		// d($this->dataPasien);
		$this->dataPasien = session()->get('pasien');
		$data = [
			'title' => 'Profile Pasien',
			'dataPasien' => $this->dataPasien
		];

		return view('pages/profile', $data);
	}

	public function setStatus($param, $value)
	{
		$value = intval($value);
		$res = "normal";
		if ($param == "detak jantung") {
			if ($value > 100) {
				$res = "tidak normal";
			}
		} else if ($param == "oksigen") {
			if ($value < 90) {
				$res = "tidak normal";
			}
		} else {
			if ($value < 30 && $value >= 39) {
				$res = "tidak normal";
			}
		}
		return $res;
	}

	public function gantiPass()
	{
		$this->dataPasien = session()->get('pasien');
		$passBaru = $this->request->getVar('newPass');
		d($passBaru);
	}
}
