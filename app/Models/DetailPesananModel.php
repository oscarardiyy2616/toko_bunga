<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPesananModel extends Model
{
    protected $table            = 'detail_pesanan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['pesanan_id', 'produk_id', 'jumlah', 'harga_satuan'];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = '';
    protected $updatedField  = '';

     protected $validationRules      = [
        'pesanan_id'  => 'required|integer',
        'produk_id'   => 'required|integer',
        'jumlah'      => 'required|integer|min_length[1]',
        'harga_satuan' => 'required|numeric',
    ];
    protected $validationMessages   = [
       'pesanan_id'  => [
            'required' => 'Pesanan ID harus diisi.',
            'integer'  => 'Pesanan ID harus berupa angka.',
        ],
        'produk_id'   => [
            'required' => 'Produk ID harus diisi.',
            'integer'  => 'Produk ID harus berupa angka.',
        ],
        'jumlah'      => [
            'required' => 'Jumlah harus diisi.',
            'integer'  => 'Jumlah harus berupa angka.',
            'min_length' => 'Jumlah minimal 1.',
        ],
        'harga_satuan' => [
            'required' => 'Harga satuan harus diisi.',
            'numeric'  => 'Harga satuan harus berupa angka.',
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

    public function getDetailPesananWithProduk($pesananId)
    {
        return $this->select('detail_pesanan.*, produk.nama as nama_produk, produk.gambar')
                    ->join('produk', 'produk.id = detail_pesanan.produk_id')
                    ->where('detail_pesanan.pesanan_id', $pesananId)
                    ->get()->getResultArray();
    }
}
