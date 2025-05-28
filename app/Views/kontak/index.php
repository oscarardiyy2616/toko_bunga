<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7 centered-form-col"> <!-- Anda bisa sesuaikan lebar kolom (misal: col-md-8 col-lg-7) -->
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Hubungi Kami</h3></div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form action="<?= site_url('kontak/kirim') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control <?= (session()->getFlashdata('errors.nama')) ? 'is-invalid' : '' ?>" value="<?= old('nama') ?>">
                            <?php if (session()->getFlashdata('errors.nama')) : ?>
                                <div class="invalid-feedback"><?= session()->getFlashdata('errors.nama') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control <?= (session()->getFlashdata('errors.email')) ? 'is-invalid' : '' ?>" value="<?= old('email') ?>">
                            <?php if (session()->getFlashdata('errors.email')) : ?>
                                <div class="invalid-feedback"><?= session()->getFlashdata('errors.email') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group mb-3">
                            <label for="subjek" class="form-label">Subjek</label>
                            <input type="text" name="subjek" id="subjek" class="form-control <?= (session()->getFlashdata('errors.subjek')) ? 'is-invalid' : '' ?>" value="<?= old('subjek') ?>">
                            <?php if (session()->getFlashdata('errors.subjek')) : ?>
                                <div class="invalid-feedback"><?= session()->getFlashdata('errors.subjek') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group mb-3">
                            <label for="pesan" class="form-label">Pesan</label>
                            <textarea name="pesan" id="pesan" class="form-control <?= (session()->getFlashdata('errors.pesan')) ? 'is-invalid' : '' ?>" rows="4"><?= old('pesan') ?></textarea>
                            <?php if (session()->getFlashdata('errors.pesan')) : ?>
                                <div class="invalid-feedback"><?= session()->getFlashdata('errors.pesan') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                        </div>
                    </form>
                </div>
                <!-- Anda bisa menambahkan card-footer jika diperlukan -->
                <!-- <div class="card-footer text-center py-3">
                    <div class="small"><a href="<?= site_url('/') ?>">Kembali ke Beranda</a></div>
                </div> -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
