<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
    <h1>Pesanan Saya</h1>
     <?php if (session()->get('success')) : ?>
        <div class="alert alert-success"><?= session()->get('success') ?></div>
    <?php endif; ?>
    <?php if (session()->get('info')) : ?>
        <div class="alert alert-success"><?= session()->get('success') ?></div>
    <?php endif; ?>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status Pesanan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pesanan as $key => $p) : ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $p['created_at'] ?></td>
                    <td>Rp <?= number_format($p['total_harga']) ?></td>
                    <td><?= $p['status_pesanan'] ?></td>
                    <td>
                        <a href="<?= site_url('pesanan/detail/' . $p['id']) ?>" class="btn btn-info">Detail</a>
                         <?php /* Tombol Bayar tidak lagi relevan jika admin yang konfirmasi */ ?>
                         <?php /* if($p['status_pesanan'] == 'Belum Dibayar'): ?> <?php endif; */ ?>
                         <?php if($p['status_pesanan'] == 'Dikirim'): ?>
                            <a href="<?= site_url('pesanan/terima/' . $p['id']) ?>" class="btn btn-primary">Terima</a
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?= $this->endSection() ?>