<?php

namespace App\Controllers;

use App\Models\BungaModel;
use App\Models\KategoriModel;

class Produk extends BaseController
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
        $data['title'] = 'Daftar Produk';
        // Untuk menampilkan nama kategori di daftar produk publik,
        // idealnya $this->bungaModel->findAll() sudah menyertakan nama kategori (misalnya dengan JOIN di model).
        // Jika belum, Anda perlu cara untuk mendapatkan nama kategori di view publik Anda.
        $data['bunga'] = $this->bungaModel->findAll();
        $data['semua_kategori'] = $this->kategoriModel->findAll(); // Untuk filter atau navigasi kategori
        return view('produk/index', $data); // Ganti 'produk/index' dengan nama view publik yang baru
    }

    public function detail($slug): string
    {
        $data['bunga'] = $this->bungaModel->where('slug', $slug)->first();
        if (!$data['bunga']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data['title'] = $data['bunga']['nama'];
        // Ambil nama kategori untuk produk ini
        if (!empty($data['bunga']['kategori_id'])) {
            $kategoriProduk = $this->kategoriModel->find($data['bunga']['kategori_id']);
            $data['nama_kategori_produk'] = $kategoriProduk ? esc($kategoriProduk['nama']) : 'Tidak Diketahui';
        } else {
            $data['nama_kategori_produk'] = 'Tidak Dikategorikan';
        }
        return view('produk/detail', $data);
    }

     public function kategori($kategoriId)
    {
        $data['bunga'] = $this->bungaModel->where('kategori_id', $kategoriId)->findAll();
        $data['kategori_aktif'] = $this->kategoriModel->find($kategoriId);
         if (!$data['kategori_aktif']) {
             throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
         }
        $data['title'] = 'Kategori: ' . esc($data['kategori_aktif']['nama']);
        $data['semua_kategori'] = $this->kategoriModel->findAll(); // Untuk filter atau navigasi kategori
        // Produk yang diambil di sini ($data['bunga']) sudah pasti dari $kategori_aktif.
        return view('produk/index', $data); // Ganti 'produk/index' dengan nama view publik yang baru
    }
}