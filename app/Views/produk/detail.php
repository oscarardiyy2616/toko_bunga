<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
    <h1><?= $bunga['nama'] ?></h1>
    <img src="<?= base_url('uploads/' . $bunga['gambar']) ?>" alt="<?= $bunga['nama'] ?>" class="img-fluid">
    <p>Kategori: <?= $bunga['kategori_id'] ?></p>
    <p><?= $bunga['deskripsi'] ?></p>
    <p>Harga: Rp <?= number_format($bunga['harga']) ?></p>
    <p>Sisa Stok: <?= $bunga['jumlah'] ?></p>
     <form action="<?= site_url('cart/tambah') ?>" method="post">
        <input type="hidden" name="id" value="<?= $bunga['id'] ?>">
        <input type="number" name="qty" value="1" min="1" max="<?= $bunga['jumlah'] ?>">
        <button type="submit" class="btn btn-success">Tambah ke Keranjang</button>
    </form>
<?= $this->endSection() ?>
