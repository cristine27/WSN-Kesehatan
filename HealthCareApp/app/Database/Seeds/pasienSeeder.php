<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class pasienSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nama'      => 'boo',
            'alamat'    => 'jln abc no 1',
            'umur'      => '34',
            'gender'    => 'pria',
            'email'     => 'boo@emai.com',
            'password'  => 'momojjang'

        ];

        // Simple Queries
        // $this->db->query("INSERT INTO users (username, email) VALUES(:username:, :email:)", $data);

        // Using Query Builder
        $this->db->table('pasien')->insert($data);
    }
}
