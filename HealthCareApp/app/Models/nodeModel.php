<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class nodeModel extends Model
{
    protected $table = 'node';
    protected $primaryKey = 'idNode';

    public function getStatusNode($id)
    {
        return $this->WHERE(['idNode' => $id])->first();
    }
}
