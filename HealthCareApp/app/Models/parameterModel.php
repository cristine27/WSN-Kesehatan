<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class parameterModel extends Model
{
    protected $table = 'parameter';
    protected $primaryKey = 'idParameter';

    public function getNamaParam($id)
    {
        return $this->where(['idParameter' => $id])->first();
    }
}
