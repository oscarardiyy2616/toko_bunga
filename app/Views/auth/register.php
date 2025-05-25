<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1><?= esc($title ?? 'Register Pelanggan') ?></h1>

    <?= service('validation')->listErrors() // Menampilkan error validasi dari controller ?>
    <?= session()->getFlashdata('error') ? '<div class="alert alert-danger">'.session()->getFlashdata('error').'</div>' : '' ?>
    <?= session()->getFlashdata('success') ? '<div class="alert alert-success">'.session()->getFlashdata('success').'</div>' : '' ?>
<form action="<?= site_url('register') ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="nama">Nama</label>
        <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama') ?>">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?= old('email') ?>">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>
    <div class="form-group">
        <label for="alamat">Alamat</label>
        <textarea name="alamat" id="alamat" class="form-control"><?= old('alamat') ?></textarea>
    </div>
    <div class="form-group">
        <label for="telepon">Telepon</label>
        <input type="text" name="telepon" id="telepon" class="form-control" value="<?= old('telepon') ?>">
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
    <a href="<?= site_url('login') ?>">Sudah punya akun? Login</a>
    </form>
</div>
<?= $this->endSection() ?>