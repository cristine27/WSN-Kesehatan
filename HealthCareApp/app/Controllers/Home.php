<?php

namespace App\Controllers;

// cara kedua tanpa namespace
use App\Models\pasien;

class Home extends BaseController
{
	protected $pasienModel;

	public function __construct()
	{
		$this->pasienModel = new pasien();
	}

	public function index()
	{

		// cara pertama dengan namespace
		// $pasien = new \App\Models\pasien();

		// cara kedua
		// $pasien = new pasien();

		$test = $this->pasienModel->findAll();
		dd($test);


		return view('welcome_message');
	}
}
