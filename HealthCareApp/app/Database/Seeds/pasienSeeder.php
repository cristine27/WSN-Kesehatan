<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class pasienSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'      => 'mimi',
                'alamat'    => 'jln abc no 1',
                'umur'      => '34',
                'gender'    => 'wanita',
                'email'     => 'mimi@emai.com',
                'password'  => 'momojjang'
            ],
            [
                'nama'      => 'dahyun',
                'alamat'    => 'jln abc no 1',
                'umur'      => '34',
                'gender'    => 'wanita',
                'email'     => 'dahyun@emai.com',
                'password'  => 'momojjang'
            ],
            [
                'nama'      => 'budi',
                'alamat'    => 'jln abc no 1',
                'umur'      => '34',
                'gender'    => 'pria',
                'email'     => 'budi@emai.com',
                'password'  => 'momojjang'
            ],
            [
                'nama'      => 'doby',
                'alamat'    => 'jln abc no 1',
                'umur'      => '34',
                'gender'    => 'pria',
                'email'     => 'doby@emai.com',
                'password'  => 'momojjang'
            ],
            [
                'nama'      => 'kiki',
                'alamat'    => 'jln abc no 1',
                'umur'      => '34',
                'gender'    => 'pria',
                'email'     => 'kiki@emai.com',
                'password'  => 'momojjang'
            ],
            [
                'nama'      => 'jeni',
                'alamat'    => 'jln abc no 1',
                'umur'      => '34',
                'gender'    => 'wanita',
                'email'     => 'jeni@emai.com',
                'password'  => 'momojjang'
            ],
            [
                'nama'      => 'tine',
                'alamat'    => 'jln abc no 1',
                'umur'      => '34',
                'gender'    => 'wanita',
                'email'     => 'tine@emai.com',
                'password'  => 'momojjang'
            ],
            [
                'nama'      => 'vana',
                'alamat'    => 'jln abc no 1',
                'umur'      => '34',
                'gender'    => 'wanita',
                'email'     => 'vana@emai.com',
                'password'  => 'momojjang'
            ],
            [
                'nama'      => 'tina',
                'alamat'    => 'jln abc no 1',
                'umur'      => '34',
                'gender'    => 'wanita',
                'email'     => 'tina@emai.com',
                'password'  => 'momojjang'
            ]
        ];

        // Simple Queries
        // $this->db->query("INSERT INTO users (username, email) VALUES(:username:, :email:)", $data);

        // Using Query Builder
        $this->db->table('pasien')->insert($data);
    }
}
