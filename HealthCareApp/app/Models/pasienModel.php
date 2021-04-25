<?php

namespace App\Models;

use CodeIgniter\Model;

class pasienModel extends Model
{
    protected $table = 'pasien'; //table apa yang digunakan
    protected $DBGroup = 'coba'; //db apa yang digunakan
    protected $primaryKey = 'idPasien'; //primary key table
    protected $useTimestamps = true;

    public function getPasien($id)
    {
        if ($id == false) {
            return $this->findAll();
        }

        return $this->where(['idPasien' => $id])->first();
    }
}
