<?php

namespace App\Models;

use CodeIgniter\Model;

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

    public function getPaginate($x = false)
    {
        if ($x == false) {
            return $this->paginate(5, 'pasien');
        }

        return $this->paginate($x, 'pasien');
    }

    public function getPager()
    {
        return $this->pager;
    }
}
