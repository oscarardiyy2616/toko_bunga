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
        foreach ($data['pesanan'] as $key => $value) {
            $data['pesanan'][$key]['detail'] = $this->detailPesananModel->getDetailPesananWithProduk($value['id']);
        }
        return view('pesanan/index', $data);
    }

    public function detail($id)
    {
        $data['title'] = 'Detail Pesanan';
        $pelanggan_id = $this->session->get('customer_id');
        $data['pesanan'] = $this->pesananModel->getPesananForCustomer($id, $pelanggan_id);

        if (!$data['pesanan'])
         {
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

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }
        $data['title'] = 'Checkout'; // Tambahkan baris ini
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

        $pelanggan_id = $this->session->get('customer_id');
        $metode_pembayaran = $this->request->getPost('metode_pembayaran');

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        // 1. Simpan ke tabel pesanan
        $pesananData = [
            'pelanggan_id' => $pelanggan_id,
            'total_harga' => $total,
            'metode_pembayaran' => $metode_pembayaran,
            'status_pesanan' => 'Menunggu Pembayaran',
        ];

        $pesananId = $this->pesananModel->insert($pesananData);

        if (!$pesananId) {
            $db = \Config\Database::connect();
            $logMessage = 'Gagal membuat pesanan. Data: ' . print_r($pesananData, true);

            if ($db->connID === false) {
                $logMessage .= ' DB Error: Database connection is not available (connID is false).';
            } else {
                $dbError = $db->error();
                if (!empty($dbError) && isset($dbError['code']) && $dbError['code'] != 0) {
                    $logMessage .= ' DB Error Code: ' . $dbError['code'] . ' DB Error Message: ' . $dbError['message'];
                } else {
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

        // 2. Simpan ke tabel detail_pesanan
        foreach ($cart as $itemId => $item) {
            $detailPesananData = [
                'pesanan_id' => $pesananId,
                'produk_id' => $itemId,
                'jumlah' => $item['qty'],
                'harga_satuan' => $item['harga'],
            ];
            $this->detailPesananModel->insert($detailPesananData);

            // Kurangi stok produk
            $bunga = $this->bungaModel->find($itemId);
            if ($bunga) {
                $sisaStok = $bunga['jumlah'] - $item['qty'];
                $this->bungaModel->update($itemId, ['jumlah' => $sisaStok]);
            }
        }

        // 3. Hapus cart setelah detail disimpan
        $this->session->remove('cart');
        return redirect()->to('/pesanan/instruksi/' . $pesananId);
    }

    public function instruksi($id)
    {
        $pesanan = $this->pesananModel->find($id);
        if (!$pesanan || $pesanan['pelanggan_id'] != $this->session->get('customer_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Pesanan tidak ditemukan atau bukan milik Anda.");
        }
        if ($pesanan['status_pesanan'] !== 'Menunggu Pembayaran') {
            $this->session->setFlashdata('info', 'Status pesanan ini tidak lagi memerlukan instruksi pembayaran.');
            return redirect()->to('/pesanan/detail/' . $id);
        }

        $data['title'] = 'Instruksi Pembayaran Pesanan #' . $id;
        $data['pesanan'] = $pesanan;
        return view('pesanan/instruksi_pembayaran', $data);
    }
     public function terima($id_pesanan)
    {
        if (!$this->session->get('iCustomer')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $pelanggan_id = $this->session->get('customer_id');
        $pesanan = $this->pesananModel->where('id', $id_pesanan)
                                      ->where('pelanggan_id', $pelanggan_id)
                                      ->first();

        if (!$pesanan) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($pesanan['status_pesanan'] !== 'Dikirim') {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dikonfirmasi diterima karena statusnya bukan "Dikirim".');
        }

        $dataUpdate = ['status_pesanan' => 'Selesai'];
        if ($this->pesananModel->update($id_pesanan, $dataUpdate)) {
            // Opsional: Tambahkan logika lain jika pesanan selesai, misal poin loyalitas
            return redirect()->to('pesanan/detail/' . $id_pesanan)->with('success', 'Pesanan telah berhasil dikonfirmasi diterima.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengupdate status pesanan.');
        }
    }

    public function batalkan($id_pesanan)
    {
        if (!$this->session->get('isCustomer')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $pelanggan_id = $this->session->get('customer_id');
        $pesanan = $this->pesananModel->where('id', $id_pesanan)
                                      ->where('pelanggan_id', $pelanggan_id)
                                      ->first();

        if (!$pesanan) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $cancellable_statuses = ['Menunggu Pembayaran', 'Menunggu Konfirmasi'];
        if (!in_array($pesanan['status_pesanan'], $cancellable_statuses) || $pesanan['status_pembayaran'] !== 'belum_bayar') {
            return redirect()->back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        // Mulai transaksi database
        $this->pesananModel->db->transStart();

        // Update status pesanan menjadi 'Dibatalkan'
        $dataUpdate = ['status_pesanan' => 'Dibatalkan'];
        $updateStatus = $this->pesananModel->update($id_pesanan, $dataUpdate);

        $stokRestored = true;
        if ($updateStatus) {
            // Kembalikan stok produk
            $detailItems = $this->detailPesananModel->where('pesanan_id', $id_pesanan)->findAll();
            if (!empty($detailItems)) {
                foreach ($detailItems as $item) {
                    if (!$this->bungaModel->updateStok($item['produk_id'], $item['jumlah'], 'tambah')) {
                        $stokRestored = false;
                        log_message('error', 'Gagal mengembalikan stok untuk produk ID: ' . $item['produk_id'] . ' pada pembatalan pesanan ID: ' . $id_pesanan);
                        break; // Hentikan jika ada kegagalan pengembalian stok
                    }
                }
            }
        }

        if ($updateStatus && $stokRestored && $this->pesananModel->db->transComplete()) {
            return redirect()->to('pesanan/detail/' . $id_pesanan)->with('success', 'Pesanan telah berhasil dibatalkan.');
        } else {
            // Jika transaksi gagal atau ada masalah
            $this->pesananModel->db->transRollback(); // Pastikan rollback jika transComplete mengembalikan false
            log_message('error', 'Gagal membatalkan pesanan atau mengembalikan stok untuk pesanan ID: ' . $id_pesanan . '. Status Update: ' . $updateStatus . ', Stok Restored: ' . $stokRestored);
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan. Silakan coba lagi atau hubungi dukungan.');
        }
    }

}