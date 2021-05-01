<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class memilikiModel extends Model
{
    protected $table = 'memiliki'; //table apa yang digunakan
    // protected $DBGroup = 'coba'; //db apa yang digunakan
    protected $primaryKey = 'idMemiliki'; //primary key table

    public function getParamid($id)
    {
        return $this->WHERE(['idNode' => $id])->first();
    }
}
