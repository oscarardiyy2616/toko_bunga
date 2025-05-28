<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <?php if (session()->getFlashdata('error')) : ?>
        <p style="color:red"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 centered-form-col"> <!-– Gunakan col-* untuk lebar dan justify-content-center untuk tengah -->
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Login Admin</h3></div>
                <div class="card-body">
                 <form method="post" action="<?= site_url('admin/login') ?>">
                 <?= csrf_field() ?>
                <input type="text" name="username" placeholder="Username" required><br><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <button type="submit">Login</button>
                </form>
                  <div class="card-footer text-center py-3">
                  <div class="small"><a href="<?= site_url('/') ?>">Kembali ke Beranda</a></div>
              </div>
          </div>
      </div>
  </div>
</body>
</html>
