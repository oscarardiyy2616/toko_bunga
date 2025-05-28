<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800"><?= esc($title) ?></h1>

    <a href="<?= site_url('admin/pesan') ?>" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesan
    </a>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Detail Pesan</h6>
            <div>
                <?php if ($pesan['status'] == 'belum_dibaca') : ?>
                    <span class="badge bg-danger">Belum Dibaca</span>
                <?php else : ?>
                    <span class="badge bg-success">Sudah Dibaca</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>Nama Pengirim:</strong>
                <p><?= esc($pesan['nama']) ?></p>
            </div>
            <div class="mb-3">
                <strong>Email Pengirim:</strong>
                <p><a href="mailto:<?= esc($pesan['email']) ?>"><?= esc($pesan['email']) ?></a></p>
            </div>
            <div class="mb-3">
                <strong>Subjek:</strong>
                <p><?= esc($pesan['subjek']) ?></p>
            </div>
            <div class="mb-3">
                <strong>Tanggal Kirim:</strong>
                <p><?= esc(date('d M Y H:i:s', strtotime($pesan['created_at']))) ?></p>
            </div>
            <hr>
            <div>
                <strong>Isi Pesan:</strong>
                <div class="mt-2 p-3 bg-light border rounded">
                    <?= nl2br(esc($pesan['pesan'])) ?>
                </div>
            </div>
        </div>
        <div class="card-footer">
             <a href="mailto:<?= esc($pesan['email']) ?>?subject=Re: <?= rawurlencode($pesan['subjek']) ?>" class="btn btn-primary">
                <i class="fas fa-reply"></i> Balas via Email
            </a>
            <a href="<?= site_url('admin/pesan/hapus/' . $pesan['id']) ?>" class="btn btn-danger float-end" onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">
                <i class="fas fa-trash"></i> Hapus Pesan
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
