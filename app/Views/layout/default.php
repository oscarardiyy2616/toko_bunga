<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Toko Bunga Indah - Temukan rangkaian bunga terbaik untuk setiap kesempatan." />
    <meta name="author" content="" />
    <title><?= $title ?? 'Toko Bunga Indah' ?></title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="<?= base_url('main_template/favicon.ico') ?>" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="<?= base_url('main_template/css/styles.css') ?>" rel="stylesheet" />
    <!-- Custom App CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>"> 
</head>
<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container px-5">
            <a class="navbar-brand" href="<?= site_url('/') ?>">Toko Bunga Indah</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link <?= (uri_string() == '/') ? 'active' : '' ?>" aria-current="page" href="<?= site_url('/') ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link <?= (uri_string() == 'produk') ? 'active' : '' ?>" href="<?= site_url('produk') ?>">Produk</a></li>
                    <li class="nav-item"><a class="nav-link <?= (uri_string() == 'kontak') ? 'active' : '' ?>" href="<?= site_url('kontak') ?>">Kontak</a></li>
                    <?php if (session()->get('isCustomer')) : ?>
                        <?php if (session()->get('customer_name')) : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCustomer" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Halo, <?= esc(session()->get('customer_name')) ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownCustomer">
                                    <li><a class="dropdown-item" href="<?= site_url('pesanan') ?>">Pesanan Saya</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= site_url('logout') ?>">Logout</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link <?= (uri_string() == 'cart') ? 'active' : '' ?>" href="<?= site_url('cart') ?>">Keranjang</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item"><a class="nav-link <?= (uri_string() == 'login') ? 'active' : '' ?>" href="<?= site_url('login') ?>">Login</a></li>
                        <li class="nav-item"><a class="nav-link <?= (uri_string() == 'register') ? 'active' : '' ?>" href="<?= site_url('register') ?>">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page content-->
    <?= $this->renderSection('content') ?>

    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container px-5"><p class="m-0 text-center text-white">Copyright &copy; Toko Bunga Indah 2024</p></div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="<?= base_url('main_template/js/scripts.js') ?>"></script>
    <!-- Custom App JS -->
    <script src="<?= base_url('assets/js/script.js') ?>"></script> 
</body>
</html>
