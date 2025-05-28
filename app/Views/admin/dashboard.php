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
        <div class="row">
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Chart Jumlah Penjualan
            </div>
            <div class="card-body"><canvas id="salesChart" width="100%" height="40"></canvas></div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area me-1"></i>
                Chart Total Pendapatan
            </div>
            <div class="card-body"><canvas id="revenueChart" width="100%" height="40"></canvas></div>
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
    <!-- 1. Muat Chart.js Library (jika belum) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
<!-- atau dari file lokal: -->
<!-- <script src="<?= base_url('admin_template/assets/vendor/chart.js/Chart.min.js') ?>"></script> -->

<!-- 2. Definisikan data dari PHP ke JavaScript -->
<script>
    // Pastikan variabel $salesData dan $revenueData dikirim dari controller Anda
    // dan berisi string JSON yang valid.
    const salesDataFromServer = <?= $salesData ?? 'null' ?>;
    const revenueDataFromServer = <?= $revenueData ?? 'null' ?>;
</script>

<!-- 3. Muat script chart custom Anda -->
<script src="<?= base_url('admin_template/assets/js/admin-custom-charts.js') ?>"></script>

<!-- Hapus atau komentari pemanggilan demo chart jika ID-nya bentrok atau tidak diperlukan lagi -->
<!-- <script src="<?= base_url('admin_template/assets/demo/chart-area-demo.js') ?>"></script> -->
<!-- <script src="<?= base_url('admin_template/assets/demo/chart-bar-demo.js') ?>"></script> -->

<?= $this->endSection() ?>