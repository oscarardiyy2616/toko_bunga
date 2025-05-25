<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
    <h1>Manajemen Kategori</h1>
    <a href="<?= site_url('admin/kategori/tambah') ?>" class="btn btn-primary mb-3">Tambah Kategori</a>
     <?php if (session()->get('success')) : ?>
        <div class="alert alert-success"><?= session()->get('success') ?></div>
    <?php endif; ?>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kategori as $key => $kat) : ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $kat['nama'] ?></td>
                    <td>
                        <a href="<?= site_url('admin/kategori/edit/' . $kat['id']) ?>" class="btn btn-warning">Edit</a>
                        <a href="<?= site_url('admin/kategori/hapus/' . $kat['id']) ?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?= $this->endSection() ?>