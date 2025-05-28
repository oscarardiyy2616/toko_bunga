<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
    <h1>Edit Produk</h1>
    <?php if (session()->get('errors')) : ?> 
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session()->get('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="<?= site_url('admin/produk/update/' . $bunga['id']) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $bunga['id'] ?>">    
    <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?= $bunga['nama'] ?>">
        </div>
         <div class="form-group">
            <label for="kategori_id">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="form-control">
                <option value="">Pilih Kategori</option>
                <?php foreach($kategori as $kat): ?>
                    <option value="<?= $kat['id'] ?>" <?= $bunga['kategori_id'] == $kat['id'] ? 'selected' : '' ?>><?= $kat['nama'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" value="<?= $bunga['harga'] ?>">
        </div>
        <div class="form-group">
            <label for="jumlah">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="<?= $bunga['jumlah'] ?>">
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control"><?= $bunga['deskripsi'] ?></textarea>
        </div>
        <div class="form-group">
            <label for="gambar">Gambar</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
            <img src="<?= base_url('uploads/' . $bunga['gambar']) ?>" alt="<?= $bunga['nama'] ?>" width="100">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
<?= $this->endSection() ?>