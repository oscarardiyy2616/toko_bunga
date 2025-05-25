<?= $this->extend("layout/default") ?>

<?= $this->section("content") ?>
    <!-- Header-->
    <header class="bg-dark py-5">
        <div class="container px-5">
            <div class="row gx-5 justify-content-center">
                <div class="col-lg-6">
                    <div class="text-center my-5">
                        <h1 class="display-5 fw-bolder text-white mb-2">Selamat Datang di Toko Bunga Indah</h1>
                        <p class="lead text-white-50 mb-4">Temukan rangkaian bunga terbaik untuk setiap kesempatan.</p>
                        <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                            <a class="btn btn-primary btn-lg px-4 me-sm-3" href="<?= site_url("produk") ?>">Lihat Produk</a>
                            <a class="btn btn-outline-light btn-lg px-4" href="<?= site_url("kontak") ?>">Hubungi Kami</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Features section (Produk Unggulan)-->
    <section class="py-5 border-bottom" id="features">
        <div class="container px-5 my-5">
            <div class="text-center mb-5">
                <h2 class="fw-bolder">Produk Unggulan</h2>
                <p class="lead mb-0">Beberapa pilihan terbaik dari kami</p>
            </div>
            <div class="row gx-5">
                <?php foreach ($bunga as $b) : ?>
                    <div class="col-lg-4 mb-5">
                        <div class="card h-100 shadow border-0">
                            <img class="card-img-top" src="<?= base_url("uploads/" . $b["gambar"]) ?>" alt="<?= $b["nama"] ?>" style="height: 200px; object-fit: cover;" />
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3"><?= $b["nama"] ?></h5>
                                <p class="card-text mb-0">Rp <?= number_format($b["harga"]) ?></p>
                            </div>
                            <div class="card-footer p-4 pt-0 bg-transparent border-top-0">
                                <div class="d-flex align-items-end justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <a class="btn btn-primary btn-sm" href="<?= site_url("produk/" . $b["slug"]) ?>">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Kategori section -->
    <section class="bg-light py-5 border-bottom">
        <div class="container px-5 my-5">
            <div class="text-center mb-5">
                <h2 class="fw-bolder">Kategori Produk</h2>
                <p class="lead mb-0">Jelajahi berdasarkan kategori</p>
            </div>
            <div class="row gx-5 justify-content-center">
                <?php foreach($kategori as $kat): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100 text-center shadow border-0">
                            <div class="card-body p-4">
                                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3 mx-auto" style="width: 3rem; height: 3rem; font-size: 1.5rem; display: flex; align-items: center; justify-content: center;"><i class="bi bi-tag-fill"></i></div>
                                <h5 class="card-title"><?= $kat["nama"] ?></h5>
                                <a href="<?= site_url("produk/kategori/" . $kat["id"]) ?>" class="btn btn-outline-primary mt-3">Lihat Kategori</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>
