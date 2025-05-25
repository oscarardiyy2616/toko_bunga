<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
    <h1>Login Admin</h1>

    <?php if (session()->get('error')) : ?>
        <div class="alert alert-danger"><?= session()->get('error') ?></div>
    <?php endif; ?>

    <form action="<?= site_url('admin/login') ?>" method="post">
        <div class="form-group">
            <label for="username">Email</label>
            <input type="email" name="username" id="username" class="form-control" value="<?= old('username') ?>">
        </div>
        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <p class="mt-3">
            <a href="<?= site_url('login') ?>">Login sebagai Pelanggan</a>
        </p>
    </form>
<?= $this->endSection() ?>