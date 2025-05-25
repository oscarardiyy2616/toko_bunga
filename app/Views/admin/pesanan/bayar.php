<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1><?= esc($title) ?></h1>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            Detail Pesanan
        </div>
        <div class="card-body">
            <p><strong>Kode Pesanan:</strong> <?= esc($pesanan['id']) ?></p>
            <p><strong>Total Tagihan:</strong> Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
            <p><strong>Status Saat Ini:</strong> <?= esc($pesanan['status_pesanan']) ?></p>
        </div>
    </div>

    <h3 class="mt-4">Pilih Metode Pembayaran (Simulasi)</h3>
    <form action="<?= site_url('pesanan/proses_pembayaran_palsu/' . $pesanan['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="metode_pembayaran">Metode Pembayaran:</label>
            <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
                <option value="">-- Pilih Metode --</option>
                <?php foreach ($metode_pembayaran_palsu as $key => $value) : ?>
                    <option value="<?= esc($key) ?>"><?= esc($value) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Bayar Sekarang (Simulasi)</button>
    </form>
    <a href="<?= site_url('pesanan/detail/' . $pesanan['id']) ?>" class="btn btn-secondary mt-3">Kembali ke Detail Pesanan</a>
</div>
<?= $this->endSection() ?>