<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class pasienModel extends Model
{
    protected $table = 'pasien'; //table apa yang digunakan
    // protected $DBGroup = 'coba'; //db apa yang digunakan
    protected $primaryKey = 'idPasien'; //primary key table
    protected $useTimestamps = true;
    // untuk crud field yang dapat di isi manual
    protected $allowedFields = ['nama', 'alamat', 'umur', 'gender', 'email', 'password']; //kolom mana saja yang boleh di isi 
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

    public function getPasienEmail($email)
    {
        $data = $this->where(['email' => $email])->first();
        if ($data) {
            dd($data);
            return $data;
        } else {
            return 0;
        }
    }

    public function getPasienId($email)
    {
        return $this->where(['email' => $email])->first();
    }

    public function getPasienPassword($pass)
    {
        $data = $this->where(['password' => $pass])->first();
        if ($data) {
            return $data;
        } else {
            return 0;
        }
    }
}
