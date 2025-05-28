<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container">

    <?php if (session()->get('error')) : ?>
        <div class="alert alert-danger"><?= session()->get('error') ?></div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 centered-form-col"> <!-- Assuming centered-form-col provides necessary centering styles -->
        <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Login Admin</h3></div>
                <div class="card-body">
                <form action="<?= site_url('admin/login') ?>" method="post">
                <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" value="<?= old('username') ?>" required>
                 <  </div>
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                    <p class="mt-3 text-center">
                    <a href="<?= site_url('login') ?>">Login sebagai Pelanggan</a>
                     </p>
                  </form>
                  </div>
                    <div class="card-footer text-center py-3">
                  <div class="small"><a href="<?= site_url('/') ?>">Kembali ke Beranda</a></div>
              </div>
          </div>
      </div>
</div>
<?= $this->endSection() ?>