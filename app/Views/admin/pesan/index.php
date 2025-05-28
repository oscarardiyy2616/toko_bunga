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
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pesan dari Pelanggan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengirim</th>
                            <th>Email</th>
                            <th>Subjek</th>
                            <th>Tanggal Kirim</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pesan_masuk)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($pesan_masuk as $pesan) : ?>
                                <tr class="<?= $pesan['status'] == 'belum_dibaca' ? 'font-weight-bold table-warning' : '' ?>">
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($pesan['nama']) ?></td>
                                    <td><?= esc($pesan['email']) ?></td>
                                    <td><?= esc($pesan['subjek']) ?></td>
                                    <td><?= esc(date('d M Y H:i', strtotime($pesan['created_at']))) ?></td>
                                    <td>
                                        <?php if ($pesan['status'] == 'belum_dibaca') : ?>
                                            <span class="badge bg-danger">Belum Dibaca</span>
                                        <?php else : ?>
                                            <span class="badge bg-success">Sudah Dibaca</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('admin/pesan/detail/' . $pesan['id']) ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= site_url('admin/pesan/hapus/' . $pesan['id']) ?>" class="btn btn-danger btn-sm" title="Hapus Pesan" onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center">Belum ada pesan masuk.</td>
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
<!-- Pastikan Anda sudah menyertakan jQuery dan DataTables di admin_layout.php -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [[ 4, "desc" ]] // Urutkan berdasarkan kolom tanggal (indeks ke-4) secara descending
        });
    });
</script>
<?= $this->endSection() ?>
