<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class periksaModel extends Model
{
    protected $table = 'periksa';
    protected $primaryKey = 'idPeriksa';

    public function getHasilPeriksa($id)
    {
        $sql =
            "SELECT idNode,hasil1,hasil2,hasil3,waktu
            From periksa
                WHERE waktu=(
                    SELECT MAX(waktu) FROM periksa WHERE idPasien = $id
                ) and idPasien = $id";

        return $this->db->query($sql);
    }

    public function getAllHasil($id)
    {
        $sql = "SELECT idNode, hasil1, hasil2, hasil3, waktu
                    FROM periksa
                        WHERE idPasien = $id
                            ORDER BY waktu desc";
        return $this->db->query($sql);
    }

    public function tabelPeriksaNode1()
    {
        $sql = "SELECT idPasien,idNode, hasil1, hasil2, hasil3, waktu
                    FROM periksa
                        where idNode = 1 and waktu = (SELECT MAX(waktu) FROM periksa WHERE idNode = 1)";

        return $this->db->query($sql);
    }

    public function tabelPeriksaNode2()
    {
        $sql = "SELECT idPasien,idNode, hasil1, hasil2, hasil3, waktu
                    FROM periksa
                        where idNode = 2 and waktu = (SELECT MAX(waktu) FROM periksa WHERE idNode = 2)";

        return $this->db->query($sql);
    }
}
