<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table            = 'pelanggan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama', 'email', 'password', 'alamat', 'telepon', 'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [
        'nama'     => 'required|alpha_space|min_length[3]',
        'email'    => 'required|valid_email|is_unique[pelanggan.email]',
        'password' => 'required|min_length[8]',
        'alamat'   => 'required',
        'telepon'  => 'required|numeric|min_length[10]|max_length[13]',
    ];
    protected $validationMessages   = [
        'nama' => [
            'required'      => 'Nama harus diisi.',
            'alpha_space'   => 'Nama hanya boleh berisi huruf dan spasi.',
            'min_length'    => 'Nama minimal 3 karakter.',
        ],
        'email' => [
            'required'    => 'Email harus diisi.',
            'valid_email' => 'Email tidak valid.',
            'is_unique'   => 'Email sudah terdaftar.',
        ],
        'password' => [
            'required'   => 'Password harus diisi.',
            'min_length' => 'Password minimal 8 karakter.',
        ],
        'alamat' => [
            'required' => 'Alamat harus diisi.',
        ],
        'telepon' => [
            'required'      => 'Telepon harus diisi.',
            'numeric'       => 'Telepon harus berupa angka.',
            'min_length'    => 'Telepon minimal 10 angka.',
            'max_length'    => 'Telepon maksimal 13 angka.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}
