<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class nodeModel extends Model
{
    protected $table = 'node'; //table apa yang digunakan
    // protected $DBGroup = 'coba'; //db apa yang digunakan
    protected $primaryKey = 'idNode'; //primary key table

    public function getStatusNode($id)
    {
        return $this->WHERE(['idNode' => $id])->first();
    }
}
