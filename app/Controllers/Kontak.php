<?php

namespace App\Controllers;

use App\Models\KontakModel;

class Kontak extends BaseController
{
    protected $kontakModel;
    protected $session;

    public function __construct()
    {
        $this->kontakModel = new KontakModel();
        $this->session = session();
    }

    public function index(): string
    {
        $data['title'] = 'Hubungi Kami';
        return view('kontak/index', $data);
    }

    public function kirimPesan()
    {
        $data = $this->request->getPost();
        if ($this->kontakModel->save($data)) {
            $this->session->setFlashdata('success', 'Pesan Anda telah terkirim!');
            return redirect()->to('/kontak');
        } else {
            $this->session->setFlashdata('errors', $this->kontakModel->errors());
            return redirect()->to('/kontak')->withInput();
        }
    }
}
