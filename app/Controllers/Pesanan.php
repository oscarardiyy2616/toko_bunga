<?php

namespace App\Controllers;

use App\Models\PesananModel;
use App\Models\DetailPesananModel;
use App\Models\BungaModel;
use CodeIgniter\Session\Session;

class Pesanan extends BaseController
{
    protected $pesananModel;
    protected $detailPesananModel;
    protected $bungaModel;
    protected $session;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->detailPesananModel = new DetailPesananModel();
        $this->bungaModel = new BungaModel();
        $this->session = session();
    }

    public function index()
    {
        $data['title'] = 'Pesanan Saya';
        $data['pesanan'] = $this->pesananModel->where('pelanggan_id', $this->session->get('customer_id'))->findAll();
        foreach($data['pesanan'] as $key => $value){
            $data['pesanan'][$key]['detail'] = $this->detailPesananModel->getDetailPesananWithProduk($value['id']);
        }
        return view('pesanan/index', $data);
    }
    public function detail($id)
    {
        $data['title'] = 'Detail Pesanan';
        $data['pesanan'] = $this->pesananModel->getPesananWithDetails($id);
        if (!$data['pesanan'] || $data['pesanan']['pelanggan_id'] != $this->session->get('customer_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data['detail_pesanan'] = $this->detailPesananModel->getDetailPesananWithProduk($id);

        return view('pesanan/detail', $data);
    }

    public function checkout()
    {
        $cart = $this->session->get('cart');
         if (!$cart || count($cart) == 0) {
            $this->session->setFlashdata('error', 'Keranjang belanja kosong.');
            return redirect()->to('/produk');
        }

        $data['title'] = 'Checkout';
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }
        $data['total'] = $total;
        return view('pesanan/checkout', $data);
    }

    public function proses_checkout()
    {
        $cart = $this->session->get('cart');
        if (!$cart || count($cart) == 0) {
            $this->session->setFlashdata('error', 'Keranjang belanja kosong.');
            return redirect()->to('/produk');
        }

        $total = 0;
         foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        // 1. Simpan ke tabel pesanan
        $pesananData = [
            'pelanggan_id' => $this->session->get('customer_id'),
            'total_harga' => $total,
            'status_pesanan' => 'Menunggu Konfirmasi', // Status baru setelah checkout
        ];

        $pesananId = $this->pesananModel->insert($pesananData);

        if (!$pesananId) {
            // Log detailed error information
            $db = \Config\Database::connect(); // Get the default database connection

            $logMessage = 'Gagal membuat pesanan. Data: ' . print_r($pesananData, true);

            // Check if the database connection's internal ID is valid
            if ($db->connID === false) {
                $logMessage .= ' DB Error: Database connection is not available (connID is false).';
            } else {
                $dbError = $db->error(); // Get the last query error
                if (!empty($dbError) && isset($dbError['code']) && $dbError['code'] != 0) { // Check if there's an actual DB error
                    $logMessage .= ' DB Error Code: ' . $dbError['code'] . ' DB Error Message: ' . $dbError['message'];
                } else {
                    // If $db->error() didn't report a specific DB error, check model validation errors
                    $modelErrors = $this->pesananModel->errors();
                    if (!empty($modelErrors)) {
                        $logMessage .= ' Model Errors: ' . print_r($modelErrors, true);
                    } else {
                        $logMessage .= ' No specific DB or Model error reported, pesananModel->insert() returned false.';
                    }
                }
            }
            log_message('error', $logMessage);

            $this->session->setFlashdata('error', 'Gagal membuat pesanan. Silakan coba lagi. [Kesalahan internal telah dicatat.]');
            return redirect()->to('/pesanan/checkout')->withInput();
        }
        // 2. Simpan ke tabel detail_pesanan (tetap dilakukan)
        foreach ($cart as $itemId => $item) { // Pastikan $itemId adalah ID produk
            $detailPesananData = [
                'pesanan_id' => $pesananId,
                'produk_id' => $itemId,
                'jumlah' => $item['qty'],
                'harga_satuan' => $item['harga'],
            ];
            $this->detailPesananModel->insert($detailPesananData);

             // Kurangi stok produk
            $bunga = $this->bungaModel->find($itemId); // Gunakan $itemId
            if ($bunga) {
                $sisaStok = $bunga['jumlah'] - $item['qty'];
                $this->bungaModel->update($itemId, ['jumlah' => $sisaStok]);
            }
        }

        // 3. Hapus cart setelah detail disimpan
        $this->session->remove('cart');
        $this->session->setFlashdata('success', 'Pesanan Anda berhasil dibuat dengan ID #' . $pesananId . ' dan sedang menunggu konfirmasi pembayaran dari admin.');
        return redirect()->to('/pesanan/detail/' . $pesananId); // Arahkan ke detail pesanan
    }
 
 
    // Method bayar() dan proses_pembayaran_palsu() bisa dihapus atau diubah
    // Jika ingin menampilkan instruksi pembayaran, method bayar() bisa dimodifikasi.
    // Untuk kesederhanaan, kita akan hapus karena konfirmasi dilakukan admin.
    public function bayar($id)
    {
         $pesanan = $this->pesananModel->find($id);
          if (!$pesanan || $pesanan['pelanggan_id'] != $this->session->get('customer_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Pesanan tidak ditemukan atau bukan milik Anda.");
        }
         if ($pesanan['status_pesanan'] !== 'Belum Dibayar') {
            $this->session->setFlashdata('info', 'Pesanan ini sudah diproses pembayarannya atau statusnya tidak memerlukan pembayaran.');
            return redirect()->to('/pesanan/detail/' . $id);
        }

        $data['title'] = 'Pembayaran Pesanan #' . $id;
        // Karena admin yang konfirmasi, halaman ini mungkin hanya menampilkan instruksi
        // atau bisa juga dihapus jika tidak ada instruksi khusus yang perlu ditampilkan ke pelanggan
        // sebelum admin konfirmasi.
        $data['pesanan'] = $pesanan;
        $this->session->setFlashdata('info', 'Pesanan Anda (' . $id . ') sedang menunggu konfirmasi pembayaran dari admin.');
        return redirect()->to('/pesanan/detail/' . $id);
        // return view('pesanan/instruksi_pembayaran', $data); // Jika ada view instruksi
    }

    public function proses_pembayaran_palsu($id)
    {
        // Method ini tidak lagi relevan jika admin yang melakukan konfirmasi.
        // Anda bisa menghapusnya.
        $this->session->setFlashdata('info', 'Konfirmasi pembayaran dilakukan oleh admin.');
        return redirect()->to('/pesanan/detail/' . $id);
    }   
     public function terima($id)
    {
        $pesanan = $this->pesananModel->find($id);
        if (!$pesanan || $pesanan['pelanggan_id'] != $this->session->get('customer_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $this->pesananModel->update($id, ['status_pesanan' => 'Selesai']);
        $this->session->setFlashdata('success', 'Pesanan diterima!');
        return redirect()->to('/pesanan/detail/' . $id);
    }
}
