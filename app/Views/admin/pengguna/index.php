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
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna Admin</h6>
            <a href="<?= site_url('admin/pengguna/tambah') ?>" class="btn btn-primary btn-sm float-right">
                <i class="fas fa-plus"></i> Tambah Admin Baru
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Tanggal Dibuat</th>
                            <!-- <th>Aksi</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($admins)) : ?>
                            <?php foreach ($admins as $admin_item) : ?>
                                <tr>
                                    <td><?= esc($admin_item['id']) ?></td>
                                    <td><?= esc($admin_item['nama']) ?></td>
                                    <td><?= esc($admin_item['email']) ?></td>
                                    <td><?= esc($admin_item['username']) ?></td>
                                    <td><?= esc(date('d M Y H:i', strtotime($admin_item['created_at']))) ?></td>
                                    <td>
                                        <!-- Tambahkan tombol edit/hapus di sini jika diperlukan di masa mendatang -->
                                        <!-- <a href="<?= site_url('admin/pengguna/edit/' . $admin_item['id']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> -->
                                        <!-- <a href="<?= site_url('admin/pengguna/hapus/' . $admin_item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus admin ini?')"><i class="fas fa-trash"></i></a> -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada pengguna admin.</td>
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
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
<?= $this->endSection() ?>
