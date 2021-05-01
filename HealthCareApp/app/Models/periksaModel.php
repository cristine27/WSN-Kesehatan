<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class periksaModel extends Model
{
    protected $table = 'periksa'; //table apa yang digunakan
    // protected $DBGroup = 'coba'; //db apa yang digunakan
    protected $primaryKey = 'idPeriksa'; //primary key table
    protected $useTimestamps = true;
    // untuk crud field yang dapat di isi manual
    // protected $allowedFields = ['nama', 'alamat', 'umur', 'gender', 'email', 'password']; //kolom mana saja yang boleh di isi 
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    public function getHasilPeriksa($id)
    {
        $sql =
            "SELECT hasil1,hasil2,hasil3,waktu
            From periksa
                WHERE waktu=(
                    SELECT MAX(waktu) FROM periksa WHERE idPasien = $id
                )";

        return $this->db->query($sql);
        // return $this->table('periksa')->like('idPasien', $id);
    }
}
