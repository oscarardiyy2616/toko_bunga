<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1><?= esc($title) ?></h1>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $cart = session()->get('cart'); ?>
            <?php foreach ($cart as $item) : ?>
                <tr>
                    <td><?= esc($item['nama']) ?></td>
                    <td><?= esc($item['qty']) ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total Keseluruhan</th>
                <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <form action="<?= site_url('pesanan/proses_checkout') ?>" method="post">
        <?= csrf_field() ?>
        <!-- Anda bisa menambahkan field lain di sini jika diperlukan, misal alamat pengiriman -->
   <div class="form-group my-3">
            <label for="metode_pembayaran">Pilih Metode Pembayaran:</label>
            <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
                <option value="">-- Pilih Metode --</option>
                <option value="Transfer Bank BCA" <?= old('metode_pembayaran') == 'Transfer Bank BCA' ? 'selected' : '' ?>>Transfer Bank BCA</option>
                <option value="Transfer Bank Mandiri" <?= old('metode_pembayaran') == 'Transfer Bank Mandiri' ? 'selected' : '' ?>>Transfer Bank Mandiri</option>
                <option value="DANA" <?= old('metode_pembayaran') == 'DANA' ? 'selected' : '' ?>>DANA</option>
                <!-- Tambahkan metode lain jika ada -->
            </select>
        </div>

        <!-- Anda bisa menambahkan field lain di sini jika diperlukan, misal alamat pengiriman, catatan, dll. -->
        <!-- <div class="form-group">
            <label for="catatan">Catatan (Opsional):</label>
            <textarea name="catatan" id="catatan" class="form-control"><?= old('catatan') ?></textarea>
        </div> -->
        <button type="submit" class="btn btn-success">Buat Pesanan & Lanjut Pembayaran</button>
    </form>
    <a href="<?= site_url('cart') ?>" class="btn btn-primary mt-2">Kembali ke Keranjang</a>

</div>
<?= $this->endSection() ?>
