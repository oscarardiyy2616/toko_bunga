<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800"><?= esc($title) ?></h1>

    <a href="<?= site_url('admin/pengguna') ?>" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengguna
    </a>

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
     <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger" role="alert"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>


    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Pengguna Admin</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('admin/pengguna/simpan') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" id="nama" name="nama" value="<?= old('nama') ?>">
                    <?php if (isset($errors['nama'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['nama']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= old('email') ?>">
                    <?php if (isset($errors['email'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= old('username') ?>">
                    <?php if (isset($errors['username'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['username']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password">
                    <?php if (isset($errors['password'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['password']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="konfirmasi_password">Konfirmasi Password</label>
                    <input type="password" class="form-control <?= isset($errors['konfirmasi_password']) ? 'is-invalid' : '' ?>" id="konfirmasi_password" name="konfirmasi_password">
                    <?php if (isset($errors['konfirmasi_password'])) : ?>
                        <div class="invalid-feedback"><?= esc($errors['konfirmasi_password']) ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
