<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Konfirmasi Pembayaran</h3></div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger">
                            <?php if (is_array(session()->getFlashdata('error'))) : ?>
                                <ul>
                                    <?php foreach (session()->getFlashdata('error') as $err) : ?>
                                        <li><?= esc($err) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <?= session()->getFlashdata('error') ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <h4>Instruksi Pembayaran:</h4>
                    <p>Silakan lakukan pembayaran ke rekening berikut:</p>
                    <ul>
                        <li><strong>Bank ABC:</strong> 123-456-7890 a.n. Toko Bunga Anda</li>
                        <li><strong>Bank XYZ:</strong> 098-765-4321 a.n. Toko Bunga Anda</li>
                    </ul>
                    <p>Total yang harus dibayar untuk Pesanan ID: <strong><?= esc($order_id ?? 'Tidak Ditemukan') ?></strong> adalah <strong>Rp <?= number_format($total_pembayaran ?? 0, 0, ',', '.') ?></strong>.</p>
                    <hr>

                    <form action="<?= site_url('pembayaran/upload') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="order_id" value="<?= esc($order_id ?? '') ?>">

                        <div class="form-group mb-3">
                            <label for="atas_nama" class="form-label">Nama Pemilik Rekening Pengirim</label>
                            <input type="text" name="atas_nama" id="atas_nama" class="form-control" value="<?= old('atas_nama') ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="bukti_pembayaran" class="form-label">Unggah Bukti Pembayaran (JPG, PNG, PDF maks 2MB)</label>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Kirim Konfirmasi</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small"><a href="<?= site_url('/') ?>">Kembali ke Beranda</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>