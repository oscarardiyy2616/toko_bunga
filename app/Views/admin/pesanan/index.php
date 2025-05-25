<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800"><?= esc($title) ?></h1>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success" role="alert"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger" role="alert"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Semua Pesanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Email Pelanggan</th>
                            <th>Total Harga</th>
                            <th>Status Pesanan</th>
                            <th>Tanggal Pesan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($semua_pesanan)) : ?>
                            <?php foreach ($semua_pesanan as $pesanan) : ?>
                                <tr>
                                    <td><?= esc($pesanan['id']) ?></td>
                                    <td><?= esc($pesanan['nama_pelanggan']) ?></td>
                                    <td><?= esc($pesanan['email_pelanggan']) ?></td>
                                    <td>Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></td>
                                    <td><?= esc($pesanan['status_pesanan']) ?></td>
                                    <td><?= esc(date('d M Y H:i', strtotime($pesanan['created_at']))) ?></td>
                                    <td>
                                        <a href="<?= site_url('admin/pesanan/detail/' . $pesanan['id']) ?>" class="btn btn-info btn-sm">Detail</a>
                                        <!-- Tambahkan tombol lain jika perlu, misal ubah status -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center">Belum ada pesanan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Jika Anda menggunakan DataTables -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
<?= $this->endSection() ?>