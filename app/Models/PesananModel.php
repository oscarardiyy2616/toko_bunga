<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table            = 'pesanan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['pelanggan_id', 'total_harga', 'status_pesanan', 'metode_pembayaran','created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

     protected $validationRules      = [
        'pelanggan_id' => 'required|integer',
        'total_harga'  => 'required|numeric',
        'status_pesanan' => 'required|in_list[Belum Dibayar,Sudah Dibayar,Dikemas,Dikirim,Selesai,Dibatalkan]',
    ];
    protected $validationMessages   = [
        'pelanggan_id' => [
            'required' => 'Pelanggan ID harus diisi.',
            'integer'  => 'Pelanggan ID harus berupa angka.',
        ],
        'total_harga' => [
            'required' => 'Total harga harus diisi.',
            'numeric'  => 'Total harga harus berupa angka.',
        ],
        'status_pesanan' => [
            'required' => 'Status pesanan harus diisi.',
            'in_list'  => 'Status pesanan tidak valid.',
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

    public function getPesananWithDetails($pesananId)
    {
        return $this->select('pesanan.*, pelanggan.nama as nama_pelanggan')
                    ->join('pelanggan', 'pelanggan.id = pesanan.pelanggan_id')
                    ->where('pesanan.id', $pesananId)
                    ->first();
    }
}
