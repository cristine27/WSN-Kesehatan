<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class pasienModel extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'idPasien';
    protected $useTimestamps = true;
    protected $allowedFields = ['nama', 'alamat', 'umur', 'gender', 'email', 'password'];
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    public function getPasien($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }

        return $this->where(['idPasien' => $id])->first();
    }

    public function getPaginate()
    {
        return $this->paginate(6, 'pasien');
    }

    public function getPager()
    {
        return $this->pager;
    }

    public function searchPasien($keyword)
    {
        return $this->table('pasien')->like('nama', $keyword);
    }

    public function getPasienbyEmail($email)
    {
        $data = $this->where(['email' => $email])->first();
        if ($data) {
            return $data;
        } else {
            return 0;
        }
    }
}
