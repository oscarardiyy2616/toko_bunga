<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
    <h1>Dashboard Admin</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Produk</h5>
                    <p class="card-text"><?= $bunga ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Kategori</h>
                    <p><?= $kategori ?></p>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>