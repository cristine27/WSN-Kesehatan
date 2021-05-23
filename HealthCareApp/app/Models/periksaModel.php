<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class periksaModel extends Model
{
    protected $table = 'periksa'; //table apa yang digunakan
    // protected $DBGroup = 'coba'; //db apa yang digunakan
    protected $primaryKey = 'idPeriksa'; //primary key table
    // untuk crud field yang dapat di isi manual
    // protected $allowedFields = ['nama', 'alamat', 'umur', 'gender', 'email', 'password']; //kolom mana saja yang boleh di isi 

    public function getHasilPeriksa($id)
    {
        $sql =
            "SELECT idNode,hasil1,hasil2,hasil3,waktu
            From periksa
                WHERE waktu=(
                    SELECT MAX(waktu) FROM periksa WHERE idPasien = $id
                )";

        return $this->db->query($sql);
        // return $this->table('periksa')->like('idPasien', $id);
    }

    public function getAllHasil($id)
    {
        $sql = "SELECT idNode, hasil1, hasil2, hasil3, waktu
                    FROM periksa
                        WHERE idPasien = $id
                            ORDER BY waktu desc";
        return $this->db->query($sql);
    }
}
