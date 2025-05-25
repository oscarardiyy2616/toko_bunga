<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
    <h1>Manajemen Produk</h1>
    <a href="<?= site_url('admin/produk/tambah') ?>" class="btn btn-primary mb-3">Tambah Produk</a>
    
    <?php if (session()->get('success')) : ?>
        <div class="alert alert-success"><?= session()->get('success') ?></div>
    <?php endif; ?>
    <table class="table">
        <thead> 
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bunga as $key => $b) : ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $b['nama'] ?></td>
                    <td><?= $b['kategori_id'] ?></td>
                    <td>Rp <?= number_format($b['harga']) ?></td>
                     <td><?= $b['jumlah'] ?></td>
                    <td><img src="<?= base_url('uploads/' . $b['gambar']) ?>" alt="<?= $b['nama'] ?>" width="100"></td>
                    <td>
                        <a href="<?= site_url('admin/produk/edit/' . $b['id']) ?>" class="btn btn-warning">Edit</a>
                        <a href="<?= site_url('admin/produk/hapus/' . $b['id']) ?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?= $this->endSection() ?>