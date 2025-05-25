<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table            = 'kategori';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama', 'slug',  'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [
        'nama' => 'required|alpha_numeric_space|min_length[3]|is_unique[kategori.nama,id,{id}]',
    ];
    protected $validationMessages   = [
        'nama' => [
            'required'      => 'Nama kategori harus diisi.',
            'alpha_space'   => 'Nama kategori hanya boleh berisi huruf dan spasi.',
            'min_length'    => 'Nama kategori minimal 3 karakter.',
            'is_unique'     => 'Nama kategori sudah ada.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateSlug'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['generateSlug'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['nama'])) {
            // Membuat slug dari nama, pastikan helper 'url' sudah di-load atau load manual jika perlu
            // helper('url'); 
            $slug = url_title($data['data']['nama'], '-', true);
            $data['data']['slug'] = $slug;
        }
        return $data;
    }
}
