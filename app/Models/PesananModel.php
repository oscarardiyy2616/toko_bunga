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
    protected $allowedFields    = ['pelanggan_id', 'total_harga', 'status_pesanan', 'status_pembayaran', 'file_bukti_pembayaran', 'atas_nama_pengirim', 'metode_pembayaran','created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

     protected $validationRules      = [
        'pelanggan_id' => 'required|integer',
        'total_harga'  => 'required|numeric',
        'status_pesanan' => 'required|in_list[Menunggu Pembayaran,Menunggu Konfirmasi,Pembayaran Diterima,Diproses,Dikemas,Dikirim,Selesai,Dibatalkan,Tertunda]',
        'status_pembayaran' => 'permit_empty|in_list[belum_bayar,menunggu_konfirmasi,lunas,gagal,dibatalkan,kadaluarsa]',
        'file_bukti_pembayaran' => 'permit_empty|max_length[255]',
        'atas_nama_pengirim' => 'permit_empty|string|max_length[100]',
        'metode_pembayaran' => 'permit_empty|string|max_length[50]',
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
        'status_pembayaran' => [
            'in_list' => 'Status pembayaran tidak valid.',
        ],
        'file_bukti_pembayaran' => [
            'max_length' => 'Nama file bukti pembayaran terlalu panjang (maks 255 karakter).',
        ],
        'atas_nama_pengirim' => [
            'string' => 'Nama pengirim harus berupa teks.',
            'max_length' => 'Nama pengirim terlalu panjang (maks 100 karakter).',
        ],
        'metode_pembayaran' => [
            'string' => 'Metode pembayaran harus berupa teks.',
            'max_length' => 'Metode pembayaran terlalu panjang (maks 50 karakter).',
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

     public function getPesananWithDetailsForAdmin($pesananId)
    {
         return $this->select('pesanan.*, 
                                pelanggan.nama as nama_pelanggan, 
                                pelanggan.email as email_pelanggan,  
                                pelanggan.telepon as telepon_pelanggan, 
                                pelanggan.alamat as alamat_pelanggan')
                    ->join('pelanggan', 'pelanggan.id = pesanan.pelanggan_id', 'left')  
                    ->where('pesanan.id', $pesananId)
                    ->first();
    }
    public function getPesananForCustomer($pesananId, $pelangganId)
    {
        // Anda bisa menyesuaikan select() sesuai kebutuhan pelanggan
        // Untuk contoh ini, kita ambil semua kolom dari pesanan yang cocok
        // Melakukan join ke tabel pelanggan untuk mendapatkan nama_pelanggan
        return $this->select('pesanan.*, pelanggan.nama as nama_pelanggan') // Ambil semua dari pesanan dan nama dari pelanggan
                    ->join('pelanggan', 'pelanggan.id = pesanan.pelanggan_id', 'left') // Gunakan left join untuk keamanan
                    ->where('pesanan.id', $pesananId)
                    ->where('pelanggan_id', $pelangganId)
                    ->first();
    }
}
