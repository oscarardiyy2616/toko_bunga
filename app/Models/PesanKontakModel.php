<?php

namespace App\Models;

use CodeIgniter\Model;

class PesanKontakModel extends Model
{
    protected $table            = 'pesan_kontak';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama', 'email', 'subjek', 'pesan', 'status'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tidak ada updated_at di tabel ini
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'nama' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|max_length[100]',
        'subjek' => 'required|min_length[5]|max_length[255]',
        'pesan' => 'required|min_length[10]',
    ];
    protected $validationMessages   = [
        'nama' => [
            'required' => 'Nama harus diisi.',
            'min_length' => 'Nama minimal 3 karakter.',
            'max_length' => 'Nama maksimal 100 karakter.',
        ],
        'email' => [
            'required' => 'Email harus diisi.',
            'valid_email' => 'Format email tidak valid.',
            'max_length' => 'Email maksimal 100 karakter.',
        ],
        'subjek' => [
            'required' => 'Subjek harus diisi.',
            'min_length' => 'Subjek minimal 5 karakter.',
            'max_length' => 'Subjek maksimal 255 karakter.',
        ],
        'pesan' => [
            'required' => 'Pesan harus diisi.',
            'min_length' => 'Pesan minimal 10 karakter.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
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
