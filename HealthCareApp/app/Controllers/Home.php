<?php

namespace App\Controllers;

// cara kedua tanpa namespace

use App\Models\pasienModel;

class Home extends BaseController
{
	protected $pasienModel;

	public function __construct()
	{
		$this->pasienModel = new pasienModel();
	}

	public function index()
	{

		// cara pertama dengan namespace
		// $pasien = new \App\Models\pasien();

		// cara kedua
		// $pasien = new pasien();

		// $test = $this->pasienModel->findAll();
		// dd($test);


		return view('pages/listPasien');
	}
}
