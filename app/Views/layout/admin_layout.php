<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Admin Dashboard Toko Bunga Indah" />
        <meta name="author" content="Toko Bunga Indah" />
        <title><?= esc($title ?? 'Admin Dashboard') ?> - Toko Bunga Indah</title>
        <!-- Menggunakan favicon yang sama dengan halaman utama -->
        <link rel="icon" type="image/x-icon" href="<?= base_url('main_template/img/Belajar-dari-Filosofi-Bunga-Mawar.jpg') ?>" />
        <!-- SB Admin CSS -->
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="<?= base_url('admin_template/css/styles.css') ?>" rel="stylesheet" />
        <!-- Font Awesome -->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Custom App CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/css/admin_style.css') ?>"> 
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="<?= site_url('admin/dashboard') ?>">Toko Bunga Admin</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search (Optional) -->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <!-- <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div> -->
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <!-- <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li> -->
                        <li><a class="dropdown-item" href="<?= site_url('logout') ?>">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link <?= (uri_string() == 'admin/dashboard') ? 'active' : '' ?>" href="<?= site_url('admin/dashboard') ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Manajemen</div>
                            <a class="nav-link <?= (strpos(uri_string(), 'admin/produk') !== false) ? 'active' : '' ?>" href="<?= site_url('admin/produk') ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                Produk
                            </a>
                            <a class="nav-link <?= (strpos(uri_string(), 'admin/kategori') !== false) ? 'active' : '' ?>" href="<?= site_url('admin/kategori') ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                                Kategori
                            </a>
                             <a class="nav-link <?= (strpos(uri_string(), 'admin/pesanan') !== false) ? 'active' : '' ?>" href="<?= site_url('admin/pesanan') ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                                Pesanan
                            </a>
                            <div class="sb-sidenav-menu-heading">Pengaturan</div>
                            <a class="nav-link <?= (strpos(uri_string(), 'admin/pengguna') !== false) ? 'active' : '' ?>" href="<?= site_url('admin/pengguna') ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                                Pengguna Admin
                            </a>
                            <a class="nav-link <?= (strpos(uri_string(), 'admin/profil') !== false) ? 'active' : '' ?>" href="<?= site_url('admin/profil') ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                                Ganti Password
                            </a>
                            <a class="nav-link <?= (strpos(uri_string(), 'admin/pesan') !== false) ? 'active' : '' ?>" href="<?= site_url('admin/pesan') ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-envelope"></i></div>
                                Pesan Masuk
                            </a>    
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?= session()->get('admin_name') ?? 'Admin' ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <!-- Render the specific page content here -->
                        <?= $this->renderSection('content') ?>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Toko Bunga Indah Admin 2024</div>
                            <div>
                                <!-- <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a> -->
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <!-- Bootstrap Bundle JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <!-- SB Admin Core JS -->
        <script src="<?= base_url('admin_template/js/scripts.js') ?>"></script>
        <!-- Chart.js (Optional) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <!-- <script src="<?= base_url('admin_template/assets/demo/chart-area-demo.js') ?>"></script>
        <script src="<?= base_url('admin_template/assets/demo/chart-bar-demo.js') ?>"></script> -->
        <!-- Simple DataTables (Optional) -->
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('admin_template/js/datatables-simple-demo.js') ?>"></script>
        <!-- Custom App JS -->
        <script src="<?= base_url('assets/js/admin_script.js') ?>"></script> 
    </body>
</html>
