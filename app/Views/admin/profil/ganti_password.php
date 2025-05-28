<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800"><?= esc($title) ?></h1>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success" role="alert"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger" role="alert"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php $errors = session()->getFlashdata('errors'); ?>
    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Ganti Password</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('admin/profil/update_password') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="password_lama">Password Lama</label>
                    <input type="password" class="form-control <?= isset($errors['password_lama']) ? 'is-invalid' : '' ?>" id="password_lama" name="password_lama">
                    <?php if (isset($errors['password_lama'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['password_lama']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password_baru">Password Baru</label>
                    <input type="password" class="form-control <?= isset($errors['password_baru']) ? 'is-invalid' : '' ?>" id="password_baru" name="password_baru">
                    <?php if (isset($errors['password_baru'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['password_baru']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="konfirmasi_password_baru">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control <?= isset($errors['konfirmasi_password_baru']) ? 'is-invalid' : '' ?>" id="konfirmasi_password_baru" name="konfirmasi_password_baru">
                    <?php if (isset($errors['konfirmasi_password_baru'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['konfirmasi_password_baru']) ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Ubah Password</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>