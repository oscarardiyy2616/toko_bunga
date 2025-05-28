<?php

namespace App\Controllers;

use App\Models\PesananModel; // Asumsi Anda memiliki PesananModel

class PembayaranController extends BaseController
{
    protected $pesananModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
    }

    // Menampilkan halaman konfirmasi pembayaran
    // Anda perlu meneruskan $order_id ke view ini, misalnya dari halaman detail pesanan
    public function konfirmasi($order_id = null)
    {
        if (!$order_id) {
            return redirect()->to('/')->with('error', 'ID Pesanan tidak valid.');
        }
        $pesanan = $this->pesananModel->find($order_id);
         if (!$pesanan) {
            return redirect()->to('pesanan')->with('error', 'Pesanan tidak ditemukan.');
        }

        // Pastikan pesanan milik customer yang login
        if ($pesanan['pelanggan_id'] != session()->get('customer_id')) {
            return redirect()->to('pesanan')->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Jika pesanan sudah dikonfirmasi atau statusnya bukan menunggu pembayaran
        if ($pesanan['status_pembayaran'] === 'menunggu_konfirmasi' || $pesanan['status_pembayaran'] === 'lunas' || $pesanan['status_pesanan'] !== 'Menunggu Pembayaran') {
            session()->setFlashdata('info', 'Pesanan ini sudah dikonfirmasi atau tidak lagi memerlukan konfirmasi pembayaran.');
            return redirect()->to('pesanan/detail/' . $order_id);
        }

        $data = [
            'title' => 'Konfirmasi Pembayaran',
            'order_id' => $order_id,
            'total_pembayaran' => $pesanan['total_harga'],                                        ];

        return view('pembayaran/konfirmasi', $data);
    }

    // Memproses unggahan bukti pembayaran
    public function upload()
    {
        if (!$this->request->is('post')) {
            return redirect()->back()->withInput()->with('error', 'Metode tidak diizinkan.');
        }
          // Ambil order_id dari POST terlebih dahulu
        $order_id = $this->request->getPost('order_id');
        $atas_nama = $this->request->getPost('atas_nama');

        // Validasi awal untuk order_id
        if (!$order_id) {
            return redirect()->back()->withInput()->with('error', 'ID Pesanan tidak valid.');
        }

        $pesanan = $this->pesananModel->find($order_id);
        if (!$pesanan || $pesanan['pelanggan_id'] != session()->get('customer_id')) {
            return redirect()->back()->withInput()->with('error', 'Pesanan tidak valid atau bukan milik Anda.');
        }
        if ($pesanan['status_pesanan'] !== 'Menunggu Pembayaran' || $pesanan['status_pembayaran'] === 'menunggu_konfirmasi' || $pesanan['status_pembayaran'] === 'lunas') {
             return redirect()->to('pembayaran/konfirmasi/' . $order_id)->with('info', 'Pesanan ini tidak dapat dikonfirmasi lagi.');
        }


        $validationRules = [
            'order_id' => 'required|numeric',
            'atas_nama' => 'required|string|max_length[100]',
            'bukti_pembayaran' => [
                'rules' => 'uploaded[bukti_pembayaran]|max_size[bukti_pembayaran,2048]|ext_in[bukti_pembayaran,jpg,jpeg,png,pdf]',
                'errors' => [
                    'uploaded' => 'Anda harus mengunggah bukti pembayaran.',
                    'max_size' => 'Ukuran file maksimal adalah 2MB.',
                    'ext_in' => 'Format file yang diizinkan hanya JPG, JPEG, PNG, atau PDF.'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $fileBukti = $this->request->getFile('bukti_pembayaran');

        if ($fileBukti->isValid() && !$fileBukti->hasMoved()) {
            $newName = $fileBukti->getRandomName();
            $fileBukti->move(WRITEPATH . 'uploads/bukti_pembayaran', $newName); // Pastikan folder ini ada dan writable
            $dataUpdate = [
                'status_pembayaran' => 'menunggu_konfirmasi',
                'file_bukti_pembayaran' => $newName,
                'atas_nama_pengirim' => $atas_nama,
                'status_pesanan' => 'Menunggu Konfirmasi', // Update juga status pesanan utama
            ];
            
            if ($this->pesananModel->update($order_id, $dataUpdate)) {
                session()->setFlashdata('success', 'Konfirmasi pembayaran berhasil dikirim. Kami akan segera memverifikasinya.');
                return redirect()->to('pesanan/detail/' . $order_id); // Arahkan ke detail pesanan setelah sukses
            }
        }

        session()->setFlashdata('error', 'Gagal mengunggah file bukti pembayaran.');
        return redirect()->back()->withInput();
    }
}