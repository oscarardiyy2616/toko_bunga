<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 centered-form-col"> <!-- Gunakan col-* untuk lebar dan justify-content-center untuk tengah -->
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Login Pelanggan</h3></div>
                <div class="card-body">
                    <form action="<?= site_url('login') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?= old('email') ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        <p class="mt-3 text-center">Belum punya akun? <a href="<?= site_url('register') ?>">Daftar di sini</a></p>
                        <!-- <p class="mt-2 text-center"><a href="#">Lupa Password?</a></p> -->
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small"><a href="<?= site_url('/') ?>">Kembali ke Beranda</a></div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>