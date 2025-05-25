<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\BungaModel;
use App\Models\KategoriModel;
use App\Models\PesananModel;
use App\models\DetailPesananModel;

class Admin extends BaseController
{
    protected $bungaModel;
    protected $kategoriModel;
    protected $pesananModel;
    protected $session;
    protected $detailPesananModel;

    public function __construct()
    {
        $this->bungaModel = new BungaModel();
        $this->kategoriModel = new KategoriModel();
        $this->pesananModel = new PesananModel();
        $this->session = session();
        $this->detailPesananModel = new DetailPesananModel();
        // Hapus redirect dari sini!
    }

    private function checkAdmin()
    {
        if (!$this->session->get('isAdmin')) {
            return redirect()->to('/admin/login')->with('error', 'Anda harus login terlebih dahulu.');
        }
        return null;
    }

    public function index(): string
    {
        if ($redirect = $this->checkAdmin()) return $redirect;
        $data['title'] = 'Dashboard Admin';
        $data['bunga'] = $this->bungaModel->countAll();
        $data['kategori'] = $this->kategoriModel->countAll();
        return view('admin/dashboard', $data);
    }
    
    public function produk(): string
    {
        if ($redirect = $this->checkAdmin()) return $redirect;
        $data['title'] = 'Manajemen Produk';
        $data['bunga'] = $this->bungaModel->findAll();
        $all_kategori = $this->kategoriModel->findAll();
        $kategori_map = [];
        foreach ($all_kategori as $kat) {
            $kategori_map[$kat['id']] = $kat['nama'];
        }
        $data['kategori_map'] = $kategori_map;
        return view('admin/produk/index', $data);
    }

    public function produk_tambah(): string
    {
        if ($redirect = $this->checkAdmin()) return $redirect;
        $data['title'] = 'Tambah Produk';
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('admin/produk/tambah', $data);
    }
public function produk_simpan(){
$validationRules = [
        'nama'        => 'required|min_length[3]|max_length[255]',
        'kategori_id' => 'required|integer',
        'harga'       => 'required|numeric',
        'jumlah'      => 'required|integer',
        'deskripsi'   => 'required',
        'gambar'      => [
            'rules'  => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif]',
            'errors' => [
                'uploaded' => 'Gambar wajib diunggah.',
                'max_size' => 'Ukuran gambar maksimal adalah 2MB.',
                'is_image' => 'File yang diunggah harus berupa gambar.',
                'mime_in'  => 'Format gambar yang diizinkan adalah JPG, JPEG, PNG, GIF.'
            ]
        ]
    ];

    if (! $this->validate($validationRules)) {
        log_message('debug', 'Validasi gagal: ' . print_r($this->validator->getErrors(), true));
        return redirect()->to('/admin/produk/tambah')->withInput()->with('errors', $this->validator->getErrors());
    }

    // Validasi berhasil, proses file upload
    $gambarFile = $this->request->getFile('gambar');
    $newName = '';

    // Pastikan file valid dan belum dipindahkan (seharusnya sudah dicek oleh 'uploaded' rule)
    if ($gambarFile->isValid() && !$gambarFile->hasMoved()) {
        $newName = $gambarFile->getRandomName();
        $uploadPath = ROOTPATH . 'public/uploads';
        if ($gambarFile->move($uploadPath, $newName)) {
            log_message('debug', "File berhasil dipindahkan. Nama baru: $newName");
        } else {
            // Seharusnya tidak terjadi jika validasi awal berhasil, tapi sebagai fallback
            log_message('error', 'Gagal memindahkan file setelah validasi. Error: ' . $gambarFile->getErrorString() . '(' . $gambarFile->getError() . ')');
            $this->session->setFlashdata('error', 'Terjadi kesalahan saat menyimpan gambar.');
            return redirect()->to('/admin/produk/tambah')->withInput();
        }
    } else {
        // Kondisi ini seharusnya tidak tercapai jika aturan 'uploaded[gambar]' ada dan bekerja
        log_message('error', 'Kondisi file tidak sesuai setelah validasi. isValid: ' . ($gambarFile->isValid()?'true':'false') . ', hasMoved: ' . ($gambarFile->hasMoved()?'true':'false'));
        $this->session->setFlashdata('error', 'File gambar tidak valid atau sudah diproses.');
        return redirect()->to('/admin/produk/tambah')->withInput();
    }
 $dataToSave = [
        'nama'        => $this->request->getPost('nama'),
        'slug'        => url_title($this->request->getPost('nama'), '-', true), // Pastikan slug juga dihandle
        'kategori_id' => $this->request->getPost('kategori_id'),
        'harga'       => $this->request->getPost('harga'),
        'jumlah'      => $this->request->getPost('jumlah'),
        'deskripsi'   => $this->request->getPost('deskripsi'),
        'gambar'      => $newName, // Simpan nama file baru
    ];

    // Simpan data menggunakan model
    if ($this->bungaModel->save($dataToSave)) {
        $this->session->setFlashdata('success', 'Produk berhasil ditambahkan.');
        return redirect()->to('/admin/produk');
    } else {
        // Jika penyimpanan model gagal (bukan karena validasi file 'uploaded')
        log_message('error', 'Gagal menyimpan produk ke DB. Model Errors: ' . print_r($this->bungaModel->errors(), true));
        // Hapus file yang sudah diupload jika penyimpanan DB gagal
        if ($newName && file_exists(ROOTPATH . 'public/uploads/' . $newName)) {
            unlink(ROOTPATH . 'public/uploads/' . $newName);
        }
        $this->session->setFlashdata('errors', $this->bungaModel->errors() ?: ['Gagal menyimpan data produk ke database.']);
        return redirect()->to('/admin/produk/tambah')->withInput();
    }
}
      public function produk_edit($id): string
    {
        if ($redirect = $this->checkAdmin()) return $redirect;
        $data['bunga'] = $this->bungaModel->find($id);
        if (!$data['bunga']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data['title'] = 'Edit Produk';
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('admin/produk/edit', $data);
    }

    public function produk_update($id)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $bungaToUpdate = $this->bungaModel->find($id);
        if (!$bungaToUpdate) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $dataToSave = $this->request->getPost(); // Get all text fields
        $dataToSave['id'] = $id; // Crucial for save() to trigger an update and use $updateRules

        $gambarFile = $this->request->getFile('gambar');

        if ($gambarFile && $gambarFile->isValid() && !$gambarFile->hasMoved()) {
            // New image uploaded and is valid
            $newName = $gambarFile->getRandomName();
            $gambarFile->move(ROOTPATH . 'public/uploads', $newName);
            $dataToSave['gambar'] = $newName; // This will be validated by $updateRules's gambar part

            // Delete old image
            if ($bungaToUpdate['gambar'] && file_exists(ROOTPATH . 'public/uploads/' . $bungaToUpdate['gambar']) && $bungaToUpdate['gambar'] != 'default.jpg') {
                unlink(ROOTPATH . 'public/uploads/' . $bungaToUpdate['gambar']);
            }
        } else if ($gambarFile && $gambarFile->getName() !== '' && !$gambarFile->isValid()) {
            // An invalid file was attempted.
            // $this->bungaModel->save($dataToSave) will fail validation due to $updateRules for 'gambar'.
            // The error message will be specific (e.g., wrong type, too large).
            // We don't set $dataToSave['gambar'] here; model validation handles the error.
        } else {
            // No new file submitted or an empty file input.
            // Unset 'gambar' from $dataToSave so the model doesn't try to save an empty string
            // for the filename, preserving the existing image filename in the DB.
            // The $updateRules with 'permit_empty' for 'gambar' will allow this.
            unset($dataToSave['gambar']);
        }

        if ($this->bungaModel->save($dataToSave)) { // save() will use $updateRules due to 'id' in $dataToSave
            $this->session->setFlashdata('success', 'Produk berhasil diupdate.');
            return redirect()->to('/admin/produk');
        } else {
            $this->session->setFlashdata('errors', $this->bungaModel->errors());
            return redirect()->to('/admin/produk/edit/' . $id)->withInput();
        }
    }
    public function produk_hapus($id)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;
        $bunga = $this->bungaModel->find($id);
        if (!$bunga) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $gambar = $bunga['gambar'];
        if ($gambar && file_exists(ROOTPATH . 'public/uploads/' . $gambar) && $gambar != 'default.jpg') {
            unlink(ROOTPATH . 'public/uploads/' . $gambar);
        }
        $this->bungaModel->delete($id);
        $this->session->setFlashdata('success', 'Produk berhasil dihapus.');
        return redirect()->to('/admin/produk');
    }

    public function kategori(): string
    {
        if ($redirect = $this->checkAdmin()) return $redirect;
        $data['title'] = 'Manajemen Kategori';
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('admin/kategori/index', $data);
    }

    public function kategori_tambah(): string
    {
        if ($redirect = $this->checkAdmin()) return $redirect;
        $data['title'] = 'Tambah Kategori';
        return view('admin/kategori/tambah', $data);
    }

    public function kategori_simpan()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;
        $data = $this->request->getPost();
        if ($this->kategoriModel->save($data)) {
            $this->session->setFlashdata('success', 'Kategori berhasil ditambahkan.');
            return redirect()->to('/admin/kategori');
        } else {
            $this->session->setFlashdata('errors', $this->kategoriModel->errors());
            return redirect()->to('/admin/kategori/tambah')->withInput();
        }
    }

    public function kategori_edit($id): string
    {
        if ($redirect = $this->checkAdmin()) return $redirect;
        $data['kategori'] = $this->kategoriModel->find($id);
        if (!$data['kategori']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data['title'] = 'Edit Kategori';
        return view('admin/kategori/edit', $data);
    }

    public function kategori_update($id)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;
        $data = $this->request->getPost();
        if ($this->kategoriModel->update($id, $data)) {
            $this->session->setFlashdata('success', 'Kategori berhasil diupdate.');
            return redirect()->to('/admin/kategori');
        } else {
            $this->session->setFlashdata('errors', $this->kategoriModel->errors());
            return redirect()->to('/admin/kategori/edit/' . $id)->withInput();
        }
    }

    public function kategori_hapus($id)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;
        $this->kategoriModel->delete($id);
        $this->session->setFlashdata('success', 'Kategori berhasil dihapus.');
        return redirect()->to('/admin/kategori');
    }
    public function pesanan()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $data['title'] = 'Manajemen Pesanan';

        // Ambil semua pesanan beserta nama pelanggan
        // Anda bisa menambahkan pagination di sini jika datanya banyak
        $data['semua_pesanan'] = $this->pesananModel
            ->select('pesanan.*, pesanan.status_pesanan as status, pelanggan.nama as nama_pelanggan, pelanggan.email as email_pelanggan')
            ->join('pelanggan', 'pelanggan.id = pesanan.pelanggan_id', 'left') // Gunakan left join untuk jaga-jaga jika ada pelanggan yang terhapus
            ->orderBy('pesanan.created_at', 'DESC')
            ->findAll();

        // Di sini Anda bisa juga mengambil detail setiap pesanan jika diperlukan,
        // namun untuk daftar awal, informasi dasar pesanan mungkin cukup.
        return view('admin/pesanan/index', $data);
    }
    public function pesanan_detail($id)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $pesanan = $this->pesananModel
            ->select('pesanan.*, pelanggan.nama as nama_pelanggan, pelanggan.email as email_pelanggan, pelanggan.alamat as alamat_pelanggan, pelanggan.telepon as telepon_pelanggan')
            ->join('pelanggan', 'pelanggan.id = pesanan.pelanggan_id', 'left')
            ->find($id);

        if (!$pesanan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Pesanan dengan ID $id tidak ditemukan.");
        }

        $data['title'] = 'Detail Pesanan #' . $pesanan['id'];
        $data['pesanan'] = $pesanan;
        $data['detail_item_pesanan'] = $this->detailPesananModel->getDetailPesananWithProduk($id);

        return view('admin/pesanan/detail', $data);
    }

    public function konfirmasi_pembayaran($id_pesanan)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $pesanan = $this->pesananModel->find($id_pesanan);

        if (!$pesanan) {
            $this->session->setFlashdata('error', 'Pesanan tidak ditemukan.');
            return redirect()->to('/admin/pesanan');
        }

        if ($pesanan['status_pesanan'] == 'Menunggu Konfirmasi') {
            // Anda bisa menambahkan field 'metode_pembayaran' jika admin juga menginputnya
            // Atau 'catatan_admin' dll.
            $this->pesananModel->update($id_pesanan, ['status_pesanan' => 'Sudah Dibayar']);
            $this->session->setFlashdata('success', 'Pembayaran untuk pesanan #' . $id_pesanan . ' berhasil dikonfirmasi.');
        } else {
            $this->session->setFlashdata('warning', 'Status pesanan #' . $id_pesanan . ' bukan "Menunggu Konfirmasi". Tidak ada tindakan dilakukan.');
        }
        return redirect()->to('/admin/pesanan/detail/' . $id_pesanan);
    }
    
}