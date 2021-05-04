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

		$data = [
			'title' => 'Home Pasien',
			'dataPasien' => $this->dataPasien,
			'hasilPeriksa' => $this->hasilPeriksa,
			'parameter' => $this->parameter
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
		dd($kumpulanparam);
		$this->paramter = $kumpulanparam;
		$data = [
			'title' => 'Pemeriksaan Pasien',
			'hasilPeriksa' => $this->hasilPeriksa,
			'parameter' => $this->parameter
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
}
