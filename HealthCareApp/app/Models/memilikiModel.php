<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class memilikiModel extends Model
{
    protected $table = 'memiliki';
    protected $primaryKey = 'idMemiliki';

    public function getParamid($id)
    {
        return $this->WHERE(['idNode' => $id])->findAll();
    }
}
