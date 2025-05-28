<?php

namespace App\Controllers;

// Hapus use KontakModel jika tidak digunakan lagi untuk tujuan lain
// use App\Models\KontakModel; 
use App\Models\PesanKontakModel;

class Kontak extends BaseController
{
    // protected $kontakModel; // Tidak digunakan lagi untuk menyimpan pesan ke admin
    protected $session;
    protected $pesanKontakModel;
    protected $validation;
    protected $helpers = ['form'];

    public function __construct()
    {
        // $this->kontakModel = new KontakModel(); // Tidak diinisialisasi lagi
        $this->session = session();
        $this->pesanKontakModel = new PesanKontakModel();
        $this->validation = \Config\Services::validation();
    }

    public function index(): string
    {
        $data['title'] = 'Hubungi Kami';
        return view('kontak/index', $data);
    }

    // Nama method 'kirimPesan' sudah sesuai dengan rute Anda
    public function kirimPesan() 
    {
        // Gunakan validasi dari PesanKontakModel
        if (!$this->pesanKontakModel->validate($this->request->getPost())) {
            return redirect()->to('/kontak')->withInput()->with('errors', $this->pesanKontakModel->errors());
        }

        $dataToSave = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'subjek' => $this->request->getPost('subjek'),
            'pesan' => $this->request->getPost('pesan'),
            'status' => 'belum_dibaca' // Set status awal
        ];

        if ($this->pesanKontakModel->insert($dataToSave)) {
            $this->session->setFlashdata('success', 'Pesan Anda berhasil dikirim. Terima kasih telah menghubungi kami.');
            return redirect()->to('/kontak');
        } else {
            // Tangani jika insert gagal (meskipun jarang terjadi jika validasi lolos)
            $this->session->setFlashdata('error', 'Gagal mengirim pesan. Silakan coba lagi.');
            return redirect()->to('/kontak')->withInput();
        }
    }
}
