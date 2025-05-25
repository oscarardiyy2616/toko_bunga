<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1><?= esc($title) ?></h1>

    <?php if (!empty($semua_kategori)) : ?>
    <nav class="nav nav-pills my-3">
        <a class="nav-link <?= !isset($kategori_aktif) ? 'active' : '' ?>"
            href="<?= site_url('produk') ?>">Semua Kategori</a>
        <?php foreach ($semua_kategori as $kat) : ?>
        <a class="nav-link <?= (isset($kategori_aktif) && $kategori_aktif['id'] == $kat['id']) ? 'active' : '' ?>"
            href="<?= site_url('produk/kategori/' . $kat['id']) ?>">
            <?= esc($kat['nama']) ?>
        </a>
        <?php endforeach; ?>
    </nav>
    <?php endif; ?>

    <?php
    // Buat peta untuk nama kategori agar mudah dicari
    $kategori_map = [];
    if (!empty($semua_kategori)) {
        foreach ($semua_kategori as $kat_item) {
            $kategori_map[$kat_item['id']] = $kat_item['nama'];
        }
    }
    ?>

    <div class="row">
        <?php if (!empty($bunga)) : ?>
        <?php foreach ($bunga as $b) : ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="<?= base_url('uploads/' . esc($b['gambar'] ?: 'default.jpg')) ?>" class="card-img-top"
                    alt="<?= esc($b['nama']) ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= esc($b['nama']) ?></h5>
                    <p class="card-text">
                        Kategori:
                        <?= esc($kategori_map[$b['kategori_id']] ?? 'Tidak Dikategorikan') ?><br>
                        Harga: Rp <?= number_format($b['harga'], 0, ',', '.') ?>
                    </p>
                    <a href="<?= site_url('produk/detail/' . esc($b['slug'] ?? $b['id'])) ?>"
                        class="btn btn-primary mt-auto">Lihat Detail</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else : ?>
        <div class="col">
            <p>Tidak ada produk untuk ditampilkan.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>