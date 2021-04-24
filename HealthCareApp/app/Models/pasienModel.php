<?php

namespace App\Models;

use CodeIgniter\Model;

class pasienModel extends Model
{
    protected $DBGroup = 'pasien';
    protected $primaryKey = 'idPasien';

    protected $useTimestamps = true;

    public function getPasien($id)
    {
        if ($id == false) {
            return $this->findAll();
        }

        return $this->where(['idPasien' => $id])->first();
    }
}
