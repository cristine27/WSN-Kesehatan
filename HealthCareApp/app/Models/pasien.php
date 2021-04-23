<?php

namespace App\Models;

use CodeIgniter\Model;

class pasien extends Model
{
    protected $DBGroup = 'pasien';
    protected $primaryKey = 'idPasien';

    protected $useTimestamps = true;
}
