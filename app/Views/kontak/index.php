<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
    <h1>Hubungi Kami</h1>
     <?php if (session()->get('success')) : ?>
        <div class="alert alert-success"><?= session()->get('success') ?></div>
    <?php endif; ?>
    <form action="<?= site_url('kontak/kirim') ?>" method="post">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama') ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= old('email') ?>">
        </div>
        <div class="form-group">
            <label for="subjek">Subjek</label>
            <input type="text" name="subjek" id="subjek" class="form-control" value="<?= old('subjek') ?>">
        </div>
        <div class="form-group">
            <label for="pesan">Pesan</label>
            <textarea name="pesan" id="pesan" class="form-control"><?= old('pesan') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
    </form>
<?= $this->endSection() ?>
