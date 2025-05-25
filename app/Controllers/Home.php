<?php

namespace App\Controllers;

use App\Models\BungaModel;
use App\Models\KategoriModel;

class Home extends BaseController
{
    protected $bungaModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->bungaModel = new BungaModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index(): string
    {
        $data['title'] = 'Toko Bunga Indah';
        $data['bunga'] = $this->bungaModel->findAll();
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('home/index', $data);
    }
}
