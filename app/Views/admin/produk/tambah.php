<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
    <h1>Tambah Produk</h1>
    <?php if (session()->get('errors')) : ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session()->get('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="<?= site_url('admin/produk/simpan') ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama') ?>">
        </div>
        <div class="form-group">
            <label for="kategori_id">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="form-control">
                <option value="">Pilih Kategori</option>
                <?php foreach($kategori as $kat): ?>
                    <option value="<?= $kat['id'] ?>" <?= old('kategori_id') == $kat['id'] ? 'selected' : '' ?>><?= $kat['nama'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" value="<?= old('harga') ?>">
        </div>
         <div class="form-group">
            <label for="jumlah">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="<?= old('jumlah') ?>">
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control"><?= old('deskripsi') ?></textarea>
        </div>
        <div class="form-group">
            <label for="gambar">Gambar</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
<?= $this->endSection() ?> 