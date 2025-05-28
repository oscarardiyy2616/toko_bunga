<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1><?= esc($title) ?></h1>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            Detail Pesanan
        </div>
        <div class="card-body">
            <p><strong>Kode Pesanan:</strong> <?= esc($pesanan['id']) ?></p>
            <p><strong>Total Tagihan:</strong> Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
            <p><strong>Metode Pembayaran:</strong> <?= esc($pesanan['metode_pembayaran']) ?></p>
            <p><strong>Status Pesanan:</strong> <?= esc($pesanan['status_pesanan']) ?></p>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            Instruksi Pembayaran
        </div>
        <div class="card-body">
            <?php if ($pesanan['metode_pembayaran'] == 'Transfer Bank BCA') : ?>
                <p>Silakan lakukan transfer ke rekening Bank BCA berikut:</p>
                <p><strong>Nomor Rekening:</strong> 123-456-7890</p>
                <p><strong>Atas Nama:</strong> Toko Bunga Indah</p>
                <p><strong>Jumlah Transfer:</strong> Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
                <p>Setelah melakukan transfer, pembayaran Anda akan segera kami periksa dan konfirmasi oleh admin kami dalam 1x24 jam.</p>
            <?php elseif ($pesanan['metode_pembayaran'] == 'Transfer Bank Mandiri') : ?>
                <p>Silakan lakukan transfer ke rekening Bank Mandiri berikut:</p>
                <p><strong>Nomor Rekening:</strong> 098-765-4321</p>
                <p><strong>Atas Nama:</strong> Toko Bunga Indah</p>
                <p><strong>Jumlah Transfer:</strong> Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
                <p>Setelah melakukan transfer, pembayaran Anda akan segera kami periksa dan konfirmasi oleh admin kami dalam 1x24 jam.</p>
            <?php elseif ($pesanan['metode_pembayaran'] == 'DANA') : ?>
                <p>Silakan lakukan pembayaran ke nomor DANA berikut:</p>
                <p><strong>Nomor DANA:</strong> 0812-3456-7890</p>
                <p><strong>Atas Nama:</strong> Toko Bunga Indah</p>
                <p><strong>Jumlah Pembayaran:</strong> Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
                <p>Pastikan Anda memasukkan kode pesanan <strong><?= esc($pesanan['id']) ?></strong> pada berita transfer jika memungkinkan.</p>
                <p>Setelah melakukan pembayaran, pembayaran Anda akan segera kami periksa dan konfirmasi oleh admin kami dalam 1x24 jam.</p>
            <?php else : ?>
                <p>Metode pembayaran tidak dikenal. Silakan hubungi customer service kami.</p>
            <?php endif; ?>
            <hr>
            <p><em>Harap lakukan pembayaran sebelum batas waktu yang ditentukan (jika ada). Pesanan yang tidak dibayar dapat dibatalkan secara otomatis.</em></p>
        </div>
    </div>

    <div class="mt-4">
        <a href="<?= site_url('pesanan/detail/' . $pesanan['id']) ?>" class="btn btn-info">Lihat Detail Pesanan Saya</a>
        <a href="<?= site_url('produk') ?>" class="btn btn-primary">Kembali Belanja</a>
    </div>

</div>
<?= $this->endSection() ?>
