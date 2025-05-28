<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
    <h1>Tambah Kategori</h1>
    <?php if (session()->get('errors')) : ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session()->get('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="<?= site_url('admin/kategori/simpan') ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="nama">Nama Kategori</label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
<?= $this->endSection() ?>