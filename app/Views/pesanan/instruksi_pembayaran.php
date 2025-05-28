<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4"><?= esc($title) ?></h3>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <p>Terima kasih telah melakukan pemesanan. Silakan lakukan pembayaran sejumlah <strong>Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></strong> ke salah satu rekening berikut:</p>
                    <ul>
                        <li><strong>Bank ABC:</strong> 123-456-7890 a.n. Toko Bunga Anda</li>
                        <li><strong>Bank XYZ:</strong> 098-765-4321 a.n. Toko Bunga Anda</li>
                        <!-- Tambahkan rekening lain jika ada -->
                    </ul>
                    <p>Mohon lakukan pembayaran sebelum batas waktu yang ditentukan (jika ada).</p>
                    <p>Setelah melakukan pembayaran, silakan lakukan konfirmasi dengan mengunggah bukti pembayaran Anda.</p>

                    <div class="text-center mt-4 mb-3">
                        <a href="<?= site_url('pembayaran/konfirmasi/' . $pesanan['id']) ?>" class="btn btn-primary btn-lg">Konfirmasi Pembayaran Sekarang</a>
                    </div>
                     <div class="text-center small"><a href="<?= site_url('pesanan/detail/' . $pesanan['id']) ?>">Kembali ke Detail Pesanan</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>