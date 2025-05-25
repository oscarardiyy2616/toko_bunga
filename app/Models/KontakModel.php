<?php

namespace App\Models;

use CodeIgniter\Model;

class KontakModel extends Model
{
    protected $table            = 'kontak';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama', 'email', 'subjek', 'pesan', 'created_at'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tidak ada updated_at

     protected $validationRules      = [
        'nama'    => 'required|alpha_space|min_length[3]',
        'email'   => 'required|valid_email',
        'subjek'  => 'required|min_length[3]',
        'pesan'   => 'required|min_length[10]',
    ];
    protected $validationMessages   = [
        'nama' => [
            'required'      => 'Nama harus diisi.',
            'alpha_space'   => 'Nama hanya boleh berisi huruf dan spasi.',
            'min_length'    => 'Nama minimal 3 karakter.',
        ],
        'email' => [
            'required'      => 'Email harus diisi.',
            'valid_email'   => 'Email tidak valid.',
        ],
         'subjek' => [
            'required'      => 'Subjek harus diisi.',
            'min_length'    => 'Subjek minimal 3 karakter.',
        ],
        'pesan' => [
            'required'      => 'Pesan harus diisi.',
            'min_length'    => 'Pesan minimal 10 karakter.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
