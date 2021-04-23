<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        // cara konek tanpa model
        // $db = \Config\Database::connect();
        // $test = $db->query("SELECT * FROM pasien");
        // dd($test);
        // foreach ($test->getResultArray() as $row) {
        //     d($row);
        // }
        return view('pages/home');
    }

    public function signin()
    {
        return view('pages/signin');
    }

    public function profile()
    {
        return view('pages/profile');
    }

    public function addPasien()
    {
        return view('pages/addPasien');
    }

    public function listPasien()
    {
        return view('pages/listPasien');
    }
}
