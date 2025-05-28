<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<?php if (session()->get('errors')) : ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session()->get('errors') as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5 centered-form-col"> <!-– Gunakan col-* untuk lebar dan justify-content-center untuk tengah -->
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-header"><h3 class="text-center font-weight-light my-4">Buat Akun</h3></div>
                        <div class="card-body">
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
                        <div class="card-footer text-center py-3">
                            <div class="small"><a href="<?= site_url('login') ?>">Sudah punya akun? Login</a></div>
                        </div>
                    </div>
                </div>    
<?= $this->endSection() ?>