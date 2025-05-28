<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\BungaModel;
use App\Models\KategoriModel;
use App\Models\PesananModel;
use App\Models\PesanKontakModel; // Tambahkan ini
use App\models\DetailPesananModel;
use App\Models\AdminModel; 
use App\Models\PelangganModel; // Tambahkan ini

class Admin extends BaseController
{
    protected $bungaModel;
    protected $kategoriModel;
    protected $pesananModel;
    protected $detailPesananModel;
    protected $pesanKontakModel; // Tambahkan ini
    protected $pelangganModel; // Tambahkan ini
    protected $session;
    protected $validation; // Tambahkan ini

    public function __construct()
    {
        $this->bungaModel = new BungaModel();
        $this->kategoriModel = new KategoriModel();
        $this->pesananModel = new PesananModel();
        $this->detailPesananModel = new DetailPesananModel();
        $this->pesanKontakModel = new PesanKontakModel(); // Tambahkan ini
        $this->pelangganModel = new PelangganModel(); // Tambahkan ini
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation(); // Tambahkan ini
        $this->adminModel = new AdminModel();
        $this->db = \Config\Database::connect();
        // Hapus redirect dari sini!
    }

    private function checkAdmin()
    {
        if (!$this->session->get('isAdmin')) {
            return redirect()->to('/admin/login')->with('error', 'Anda harus login terlebih dahulu.');
        }
        return null;
    }

    private function _getMonthlyRevenueData()
    {
        // Ambil data pendapatan per bulan untuk pesanan yang sudah dibayar atau selesai
        $results = $this->pesananModel
            ->select("DATE_FORMAT(created_at, '%b %Y') AS month_year_label, DATE_FORMAT(created_at, '%Y-%m') AS year_month_sort, SUM(total_harga) AS total_revenue")
            ->whereIn('status_pesanan', ['Sudah Dibayar', 'Selesai'])
            ->groupBy("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('year_month_sort', 'ASC')
            // ->limit(12) // Batasi misal 12 bulan terakhir jika perlu
            ->findAll();

        $labels = [];
        $values = [];
        if ($results) {
            foreach ($results as $row) {
                $labels[] = $row['month_year_label'];
                $values[] = (float)$row['total_revenue'];
            }
        }
        return ['labels' => $labels, 'values' => $values];
    }

    private function _getMonthlySalesData()
    {
        // Ambil data jumlah unit terjual per bulan untuk pesanan yang sudah dibayar atau selesai
        $results = $this->detailPesananModel
            ->select("DATE_FORMAT(pesanan.created_at, '%b %Y') AS month_year_label, DATE_FORMAT(pesanan.created_at, '%Y-%m') AS year_month_sort, SUM(detail_pesanan.jumlah) AS total_units_sold")
            ->join('pesanan', 'pesanan.id = detail_pesanan.pesanan_id')
            ->whereIn('pesanan.status_pesanan', ['Sudah Dibayar', 'Selesai'])
            ->groupBy("DATE_FORMAT(pesanan.created_at, '%Y-%m')")
            ->orderBy('year_month_sort', 'ASC')
            // ->limit(12) // Batasi misal 12 bulan terakhir jika perlu
            ->findAll();

        $labels = [];
        $values = [];
        if ($results) {
            // Pastikan labelnya konsisten jika salah satu chart punya data di bulan tertentu dan yang lain tidak
            // Untuk contoh ini, kita asumsikan kedua query akan menghasilkan set bulan yang mirip
            // atau Chart.js akan menanganinya dengan baik jika labelnya sama.
            // Idealnya, Anda membuat satu set label master (misal 6 bulan terakhir)
            // dan mengisi nilai 0 jika tidak ada data untuk bulan tersebut.
            foreach ($results as $row) {
                $labels[] = $row['month_year_label'];
                $values[] = (int)$row['total_units_sold'];
            }
        }
        return ['labels' => $labels, 'values' => $values];
    }

    public function index(): string
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $data['title'] = 'Dashboard Admin';
        $data['bunga'] = $this->bungaModel->countAll();
        $data['kategori'] = $this->kategoriModel->countAll();

        // Ambil data untuk chart
        $revenueChartData = $this->_getMonthlyRevenueData();
        $salesChartData = $this->_getMonthlySalesData();

        // Jika label dari kedua chart berbeda, Anda mungkin ingin menyatukannya
        // Untuk contoh ini, kita akan menggunakan label dari revenue data jika ada,
        // atau sales data jika revenue kosong. Idealnya, Anda membuat set label yang konsisten.
        $data['revenueData'] = json_encode($revenueChartData);
        
        // Jika Anda ingin label sales chart sama persis dengan revenue chart (misal untuk sinkronisasi sumbu X)
        // Anda perlu memproses $salesChartData agar memiliki label yang sama dengan $revenueChartData.labels
        // dan mengisi nilai 0 untuk bulan yang tidak ada penjualan.
        // Untuk kesederhanaan, kita kirim apa adanya dulu.
        $data['salesData'] = json_encode($salesChartData);

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
    public function produk_simpan()
    {
        if ($redirect = $this->checkAdmin()) return $redirect; // Tambahkan pengecekan admin di sini

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
         $pesanan = $this->pesananModel->getPesananWithDetailsForAdmin($id); 
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
        if ($pesanan['status_pembayaran'] === 'menunggu_konfirmasi') {
            // Update status pembayaran menjadi 'lunas' dan status pesanan menjadi 'Diproses'
            $updateData = [
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'Diproses', // Atau status selanjutnya setelah pembayaran lunas
            ];
            $this->pesananModel->update($id_pesanan, $updateData);
            $this->session->setFlashdata('success', 'Pembayaran untuk pesanan #' . $id_pesanan . ' berhasil dikonfirmasi.');
         } elseif ($pesanan['status_pembayaran'] === 'lunas') {
            $this->session->setFlashdata('warning', 'Status pesanan #' . $id_pesanan . ' sudah lunas.');
         }
        return redirect()->to('/admin/pesanan/detail/' . $id_pesanan);
    }
    public function lihat_bukti_pembayaran($id_pesanan)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $pesanan = $this->pesananModel->find($id_pesanan);

        if (!$pesanan || empty($pesanan['file_bukti_pembayaran'])) {
            // Pesanan tidak ditemukan atau tidak ada bukti pembayaran
            log_message('error', "Admin coba lihat bukti: Pesanan ID $id_pesanan tidak ditemukan atau tidak ada file bukti.");
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Bukti pembayaran untuk pesanan ID $id_pesanan tidak ditemukan.");
        }

        $filePath = WRITEPATH . 'uploads/bukti_pembayaran/' . $pesanan['file_bukti_pembayaran'];

        if (!file_exists($filePath)) {
            // File tidak ditemukan di server
            log_message('error', "Admin coba lihat bukti: File tidak ditemukan di server. Path: " . $filePath . " untuk pesanan ID: " . $id_pesanan);
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("File bukti pembayaran tidak ditemukan di server. Path yang dicari: " . $filePath);
        }

        // Sajikan file agar bisa dilihat/diunduh oleh admin
          // return $this->response->download($filePath, null); // Ini akan memaksa download

        // Coba tampilkan inline
        $mime = mime_content_type($filePath);
        if (!$mime) {
            // Jika tipe mime tidak terdeteksi, fallback ke download
            log_message('error', "Gagal mendeteksi tipe MIME untuk file: " . $filePath);
            return $this->response->download($filePath, null)->setFileName(basename($filePath));
        }

        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit; // Penting untuk menghentikan eksekusi script setelah mengirim file
    }

    public function ubah_status_pesanan($id_pesanan)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $pesananSebelumnya = $this->pesananModel->find($id_pesanan);
        if (!$pesananSebelumnya) {
            $this->session->setFlashdata('error', 'Pesanan tidak ditemukan.');
            return redirect()->to('/admin/pesanan');
        }

        $new_status = $this->request->getPost('new_status');
        $allowed_statuses = ['Sudah Dibayar', 'Diproses', 'Dikemas', 'Dikirim', 'Selesai', 'Tertunda', 'Dibatalkan'];

        if (!in_array($new_status, $allowed_statuses)) {
            $this->session->setFlashdata('error', 'Status baru tidak valid.');
            return redirect()->to('/admin/pesanan/detail/' . $id_pesanan);
        }
         // Mulai transaksi
        $this->db->transStart();

        // Logika pengembalian stok jika pesanan dibatalkan
        if ($new_status === 'Dibatalkan' && $pesananSebelumnya['status_pesanan'] !== 'Dibatalkan') {
            $detailItems = $this->detailPesananModel->where('pesanan_id', $id_pesanan)->findAll();
            if ($detailItems) {
                foreach ($detailItems as $item) {
                    $produk = $this->bungaModel->find($item['produk_id']);
                    if ($produk) {
                        $stokBaru = $produk['jumlah'] + $item['jumlah'];
                        if (!$this->bungaModel->update($item['produk_id'], ['jumlah' => $stokBaru])) {
                            // Jika gagal update stok, log error dan bisa hentikan transaksi lebih awal jika diperlukan
                            log_message('error', 'Gagal mengembalikan stok untuk produk ID: ' . $item['produk_id'] . ' pada pesanan ID: ' . $id_pesanan);
                        }
                    }
                }
                log_message('info', "Stok dikembalikan untuk pesanan ID: $id_pesanan yang dibatalkan.");
            }
        }

        // Update status pesanan
        $updateData = ['status_pesanan' => $new_status];
         $this->pesananModel->update($id_pesanan, $updateData); // Tidak perlu cek return di sini, transStatus akan menangani

        // Selesaikan transaksi
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            // Transaksi gagal, log error, set flashdata error
            log_message('error', 'Transaksi gagal saat mengubah status pesanan ID: ' . $id_pesanan . ' dan mengembalikan stok. Model Errors: ' . print_r($this->pesananModel->errors(), true));
            $this->session->setFlashdata('error', 'Gagal mengubah status pesanan dan mengembalikan stok karena kesalahan sistem.');
        } else {
            // Transaksi berhasil
            $this->session->setFlashdata('success', 'Status pesanan #' . $id_pesanan . ' berhasil diubah menjadi "' . $new_status . '". Stok telah disesuaikan jika perlu.');
        

        }

        return redirect()->to('/admin/pesanan/detail/' . $id_pesanan);
    }

    // --- PENGELOLAAN ADMIN PENGGUNA ---
    public function pengguna_index()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $data['title'] = 'Manajemen Pengguna Admin';
        $data['admins'] = $this->adminModel->findAll(); // adminModel perlu diinisialisasi di constructor
        return view('admin/pengguna/index', $data);
    }

    public function pengguna_tambah()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $data['title'] = 'Tambah Pengguna Admin Baru';
        return view('admin/pengguna/tambah', $data);
    }

    public function pengguna_simpan()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $rules = [
            'nama' => 'required|alpha_space|min_length[3]',
            'email' => 'required|valid_email|is_unique[admin.email]',
            'username' => 'required|alpha_numeric|min_length[3]|is_unique[admin.username]', // Tambahkan jika username digunakan dan harus unik
            'password' => 'required|min_length[8]',
            'konfirmasi_password' => 'required|matches[password]'
        ];

        $messages = [
            'nama' => ['required' => 'Nama harus diisi.', 'alpha_space' => 'Nama hanya boleh huruf dan spasi.', 'min_length' => 'Nama minimal 3 karakter.'],
            'email' => ['required' => 'Email harus diisi.', 'valid_email' => 'Format email tidak valid.', 'is_unique' => 'Email sudah terdaftar.'],
            'username' => ['required' => 'Username harus diisi.', 'alpha_numeric' => 'Username hanya boleh huruf dan angka.', 'min_length' => 'Username minimal 3 karakter.', 'is_unique' => 'Username sudah digunakan.'],
            'password' => ['required' => 'Password harus diisi.', 'min_length' => 'Password minimal 8 karakter.'],
            'konfirmasi_password' => ['required' => 'Konfirmasi password harus diisi.', 'matches' => 'Konfirmasi password tidak cocok dengan password.']
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->to('/admin/pengguna/tambah')->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataToSave = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'), // Model akan melakukan hashing
        ];

        if ($this->adminModel->save($dataToSave)) {
            $this->session->setFlashdata('success', 'Pengguna admin baru berhasil ditambahkan.');
            return redirect()->to('/admin/pengguna');
        } else {
            $this->session->setFlashdata('error', 'Gagal menambahkan pengguna admin baru.');
            return redirect()->to('/admin/pengguna/tambah')->withInput();
        }
    }

    public function profil() // Untuk ganti password admin sendiri
    {
        if ($redirect = $this->checkAdmin()) return $redirect;
 
        $data['title'] = 'Ganti Password Saya';
        $data['admin'] = $this->adminModel->find($this->session->get('admin_id'));
        return view('admin/profil/ganti_password', $data);
    }

    public function update_password()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $adminId = $this->session->get('admin_id');
        $admin = $this->adminModel->find($adminId);

        $rules = [
            'password_lama' => 'required',
            'password_baru' => 'required|min_length[8]',
            'konfirmasi_password_baru' => 'required|matches[password_baru]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/profil')->withInput()->with('errors', $this->validator->getErrors());
        }

        $passwordLama = $this->request->getPost('password_lama');
        $passwordBaru = $this->request->getPost('password_baru');

        if (!password_verify($passwordLama, $admin['password'])) {
            $this->session->setFlashdata('error', 'Password lama salah.');
            return redirect()->to('/admin/profil')->withInput();
        }

        if ($this->adminModel->update($adminId, ['password' => $passwordBaru])) { // Model akan hash password baru
            $this->session->setFlashdata('success', 'Password berhasil diubah.');
            return redirect()->to('/admin/profil');
        } else {
            $this->session->setFlashdata('error', 'Gagal mengubah password.');
            return redirect()->to('/admin/profil')->withInput();
        }
    }

    // --- MANAJEMEN PESAN KONTAK ---
    public function pesan_index()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $data['title'] = 'Daftar Pesan Masuk';
        $data['pesan_masuk'] = $this->pesanKontakModel->orderBy('created_at', 'DESC')->findAll();
        return view('admin/pesan/index', $data);
    }

    public function pesan_detail($id)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $pesan = $this->pesanKontakModel->find($id);
        if (!$pesan) {
            $this->session->setFlashdata('error', 'Pesan tidak ditemukan.');
            return redirect()->to('/admin/pesan');
        }

        // Tandai sebagai sudah dibaca jika statusnya belum dibaca
        if ($pesan['status'] == 'belum_dibaca') {
            $this->pesanKontakModel->update($id, ['status' => 'sudah_dibaca']);
            // Muat ulang data pesan setelah update status
            $pesan = $this->pesanKontakModel->find($id);
        }

        $data['title'] = 'Detail Pesan: ' . esc($pesan['subjek']);
        $data['pesan'] = $pesan;
        return view('admin/pesan/detail', $data);
    }

    public function pesan_hapus($id)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        if ($this->pesanKontakModel->delete($id)) {
            $this->session->setFlashdata('success', 'Pesan berhasil dihapus.');
        } else {
            $this->session->setFlashdata('error', 'Gagal menghapus pesan.');
        }
        return redirect()->to('/admin/pesan');
    }
    
    
}