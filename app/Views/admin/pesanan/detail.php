<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800"><?= esc($title) ?></h1>

    <a href="<?= site_url('admin/pesanan') ?>" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
    </a>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success" role="alert"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger" role="alert"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('warning')) : ?>
        <div class="alert alert-warning" role="alert"><?= session()->getFlashdata('warning') ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pesanan</h6>
                </div>
                <div class="card-body">
                    <p><strong>ID Pesanan:</strong> <?= esc($pesanan['id']) ?></p>
                    <p><strong>Tanggal Pesan:</strong> <?= esc(date('d M Y H:i', strtotime($pesanan['created_at']))) ?></p>
                    <p><strong>Total Harga:</strong> Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
                    <p><strong>Status Pesanan:</strong> <?= esc($pesanan['status_pesanan']) ?></p>
                    <?php if (isset($pesanan['metode_pembayaran'])) : ?>
                        <p><strong>Metode Pembayaran:</strong> <?= esc($pesanan['metode_pembayaran']) ?></p>
                    <?php endif; ?><br>

                    <?php if ($pesanan['status_pesanan'] == 'Menunggu Konfirmasi') : ?>
                        <form action="<?= site_url('admin/pesanan/konfirmasi_pembayaran/' . $pesanan['id']) ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin mengkonfirmasi pembayaran untuk pesanan ini?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Konfirmasi Pembayaran</button>
                        </form>
                    <?php endif; ?>
                    <!-- Tambahkan form untuk ubah status pesanan jika diperlukan -->
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pelanggan</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nama Pelanggan:</strong> <?= esc($pesanan['nama_pelanggan']) ?></p>
                    <p><strong>Email:</strong> <?= esc($pesanan['email_pelanggan']) ?></p>
                    <p><strong>Telepon:</strong> <?= esc($pesanan['telepon_pelanggan'] ?? '-') ?></p>
                    <p><strong>Alamat:</strong> <?= esc($pesanan['alamat_pelanggan'] ?? '-') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Item Pesanan (Riwayat Transaksi)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($detail_item_pesanan)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($detail_item_pesanan as $item) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?php if (!empty($item['gambar'])) : ?>
                                            <img src="<?= base_url('uploads/' . $item['gambar']) ?>" alt="<?= esc($item['nama_produk']) ?>" width="80">
                                        <?php else : ?>
                                            <span>Tidak ada gambar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($item['nama_produk']) ?></td>
                                    <td><?= esc($item['jumlah']) ?></td>
                                    <td>Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                                    <td>Rp <?= number_format($item['jumlah'] * $item['harga_satuan'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada item dalam pesanan ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>