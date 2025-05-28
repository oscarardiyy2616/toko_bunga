<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
    <h1>Login Pelanggan</h1>
    <?php if (session()->get('error')) : ?>
        <div class="alert alert-danger"><?= session()->get('error') ?></div>
    <?php endif; ?>
    <form action="<?= site_url('login') ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= old('email') ?>">
        </div>
        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="<?= site_url('register') ?>">Belum punya akun? Daftar</a>
    </form>
    <hr>
    <p>
        <a href="<?= site_url('admin/login') ?>">Login sebagai Admin</a>
    </p>
<?= $this->endSection() ?>