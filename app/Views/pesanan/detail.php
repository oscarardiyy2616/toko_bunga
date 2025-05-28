<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h1 class="mb-4"><?= esc($title ?? 'Detail Pesanan') ?></h1>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success" role="alert"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger" role="alert"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (!empty($pesanan)) : ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pesanan</h5>
            </div>
            <div class="card-body">
                <p><strong>ID Pesanan:</strong> <?= esc($pesanan['id']) ?></p>
                <p><strong>Tanggal Pesanan:</strong> <?= esc(date('d M Y H:i', strtotime($pesanan['created_at']))) ?></p>
                <p><strong>Total Harga:</strong> Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
                <p><strong>Status Pesanan:</strong> <span class="badge bg-info text-white"><?= esc($pesanan['status_pesanan']) ?></span></p>
                <p><strong>Status Pembayaran:</strong> <span class="badge bg-warning text-dark"><?= esc(ucwords(str_replace('_', ' ', $pesanan['status_pembayaran'] ?? 'Belum Bayar'))) ?></span></p>
                <p><strong>Metode Pembayaran:</strong> <?= esc($pesanan['metode_pembayaran'] ?? '-') ?></p>

                <!-- Tombol Aksi Pelanggan -->
                <div class="mt-3">
                    <?php if ($pesanan['status_pesanan'] == 'Dikirim') : ?>
                        <form action="<?= site_url('pesanan/terima/' . $pesanan['id']) ?>" method="post" class="d-inline-block" onsubmit="return confirm('Apakah Anda yakin sudah menerima pesanan ini?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Pesanan Diterima</button>
                        </form>
                    <?php endif; ?>

                    <?php
                    // Status pesanan yang boleh dibatalkan pelanggan & belum dibayar
                    $cancellable_statuses = ['Menunggu Pembayaran', 'Menunggu Konfirmasi'];
                    if (in_array($pesanan['status_pesanan'], $cancellable_statuses) && $pesanan['status_pembayaran'] == 'belum_bayar') :
                    ?>
                        <form action="<?= site_url('pesanan/batalkan/' . $pesanan['id']) ?>" method="post" class="d-inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-times-circle"></i> Batalkan Pesanan</button>
                        </form>
                    <?php endif; ?>
                </div>
                <!-- Akhir Tombol Aksi Pelanggan -->
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Item Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
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
                            <?php if (!empty($detail_pesanan)) : ?>
                                <?php foreach ($detail_pesanan as $key => $item) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><img src="<?= base_url('uploads/' . esc($item['gambar'] ?? 'default.jpg')) ?>" alt="<?= esc($item['nama_produk']) ?>" width="60"></td>
                                        <td><?= esc($item['nama_produk']) ?></td>
                                        <td><?= esc($item['jumlah']) ?></td>
                                        <td>Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($item['jumlah'] * $item['harga_satuan'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr><td colspan="6" class="text-center">Tidak ada item dalam pesanan ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="alert alert-warning">Detail pesanan tidak ditemukan.</div>
    <?php endif; ?>
    <a href="<?= site_url('pesanan') ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan Saya</a>
</div>
<?= $this->endSection() ?>