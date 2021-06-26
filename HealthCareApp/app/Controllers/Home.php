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
	}

	public function index()
	{
		$this->dataPasien = session()->get('pasien');
		$check = false;
		if ($this->dataPasien['password'] == $this->dataPasien['email']) {
			$check = true;
		}
		$newData = $this->pasienModel->getPasien($this->dataPasien['idPasien']);
		if ($this->dataPasien['password'] != $newData['password']) {
			$this->dataPasien = $newData;
			session()->set('pasien', $newData);
		}
		$data = [
			'title' => 'Home Pasien',
			'dataPasien' => $this->dataPasien,
			'hasilPeriksa' => $this->hasilPeriksa,
			'parameter' => $this->parameter,
			'flag' => $check
		];
		return view('pages/homePasien', $data);
	}

	public function getHasilPantau()
	{
		$this->dataPasien = session()->get('pasien');
		$dataPeriksa = $this->periksaModel->getHasilPeriksa($this->dataPasien['idPasien']);
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
			$idNode = $hasil['idNode'];
			$idParam = $this->memilikiModel->getParamid($idNode);

			$index = 0;
			foreach ($idParam as $id) {
				$namaParam = $this->parameterModel->getNamaParam($id['idParameter']);
				$kumpulanparam[$index] = $namaParam;
				$kumpulanStatus[$index] = $this->setStatus($namaParam['namaParameter'], $hasil['hasil' . strval($index + 1)], $this->dataPasien['umur']);
				$index++;
			}
		}

		if ($check == false) {
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
						'namaParameter' => "-"
					]
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
		$this->dataPasien = session()->get('pasien');
		$data = [
			'title' => 'Profile Pasien',
			'dataPasien' => $this->dataPasien,
			'validation' => \Config\Services::validation()
		];

		return view('pages/profile', $data);
	}

	public function setStatus($param, $value, $age)
	{
		$value = intval($value);
		$res = "normal";
		$age = intval($age);
		$max = 220 - $age;
		if ($param == "Detak jantung") {
			if ($value < 60 || $value > 100 || $value > $max) {
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

	public function gantiPass()
	{
		// d("masuk fungsi ganti Pass");
		if (!$this->validate([
			'newPass' => [
				'rules' => 'required|min_length[8]',
				'errors' => [
					'required' => 'password harus diisi.',
					'min_length[8]' => 'panjang password minimal 8 karakter.'
				]
			]
		])) {
			$validation = \Config\Services::validation();
			return redirect()->to('/Home/getPasienProfile')->withInput()->with('validation', $validation);
		}
		$passBaru = $this->request->getVar('newPass');
		$this->dataPasien = session()->get('pasien');

		$this->pasienModel->save([
			'idPasien' => $this->dataPasien['idPasien'],
			'nama' =>  $this->dataPasien['nama'],
			'alamat'    =>  $this->dataPasien['alamat'],
			'umur' =>  $this->dataPasien['umur'],
			'gender' =>  $this->dataPasien['gender'],
			'email'     =>  $this->dataPasien['email'],
			'password'  => $passBaru
		]);

		session()->setFlashdata('pesan', 'Password berhasil diubah.');
		return redirect()->to('/Home');
	}

	public function getRiwayat()
	{
		$tanggal = $this->request->getVar('tanggal');
		$this->dataPasien = session()->get('pasien');

		$dataPeriksa = ($this->periksaModel->getAllHasil($this->dataPasien['idPasien']));

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
				$kumpulanStatus[$j][$index] = $this->setStatus($namaParam['namaParameter'], $res['hasil' . strval($index + 1)], $this->dataPasien['umur']);
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
						'namaParameter' => "-"
					]
				]
			];

			$kumpulanStatus = [
				0 => "-"
			];
		}

		$data = [
			'title' => 'Riwayat Pasien',
			'pasien' => $this->dataPasien,
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
		return view('pages/riwayat', $data);
	}
}
