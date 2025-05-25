<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
    <h1>Detail Pesanan</h1>
    <p>Kode Pesanan: <b><?= $pesanan['id'] ?></b></p>
    <p>Tanggal Pesanan: <?= $pesanan['created_at'] ?></p>
    <p>Nama Pelanggan: <?= $pesanan['nama_pelanggan'] ?></p>
    <p>Status Pesanan: <b><?= $pesanan['status_pesanan'] ?></b></p>
    <p>Total Harga: Rp <?= number_format($pesanan['total_harga']) ?></p>

    <h3>Detail Produk</h3>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Gambar</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detail_pesanan as $key => $d) : ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $d['nama_produk'] ?></td>
                     <td><img src="<?= base_url('uploads/' . $d['gambar']) ?>" alt="<?= $d['nama_produk'] ?>" width="100"></td>
                    <td><?= $d['jumlah'] ?></td>
                    <td>Rp <?= number_format($d['harga_satuan']) ?></td>
                    <td>Rp <?= number_format($d['jumlah'] * $d['harga_satuan']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?= $this->endSection() ?>