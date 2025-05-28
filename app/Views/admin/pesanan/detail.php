<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800"><?= esc($title) ?></h1>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('warning')) : ?>
        <div class="alert alert-warning"><?= session()->getFlashdata('warning') ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Pesanan</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID Pesanan:</strong> <?= esc($pesanan['id']) ?></p>
                    <p><strong>Tanggal Pesanan:</strong> <?= esc(date('d M Y H:i', strtotime($pesanan['created_at']))) ?></p>
                    <p><strong>Total Harga:</strong> Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
                    <p><strong>Metode Pembayaran:</strong> <?= esc($pesanan['metode_pembayaran'] ?? '-') ?></p>
                </div>
                <div class="col-md-6">
                    <?php
                    $status_pesanan_badge = 'badge-secondary'; // Default
                    switch ($pesanan['status_pesanan']) {
                        case 'Selesai':
                            $status_pesanan_badge = 'badge-success';
                            break;
                        case 'Dikirim':
                        case 'Dikemas':
                            $status_pesanan_badge = 'badge-primary';
                            break;
                        case 'Diproses':
                        case 'Pembayaran Diterima':
                            $status_pesanan_badge = 'badge-info';
                            break;
                        case 'Menunggu Konfirmasi':
                        case 'Tertunda':
                        case 'Menunggu Pembayaran': // Tambahkan jika status ini mungkin muncul di sini
                            $status_pesanan_badge = 'badge-warning';
                            break;
                        case 'Dibatalkan':
                            $status_pesanan_badge = 'badge-danger';
                            break;
                    }
                    ?>
                     <p><strong>Status Pesanan:</strong> <span class="badge <?= $status_pesanan_badge ?> py-2 px-3"><?= esc($pesanan['status_pesanan']) ?></span></p>
                    <?php
                    $status_pembayaran_badge = 'badge-secondary'; // Default
                    $status_pembayaran_text = $pesanan['status_pembayaran'] ?? 'belum_bayar';
                    switch ($status_pembayaran_text) {
                        case 'lunas':
                            $status_pembayaran_badge = 'badge-success';
                            break;
                        case 'menunggu_konfirmasi':
                            $status_pembayaran_badge = 'badge-info';
                            break;
                        case 'belum_bayar':
                            $status_pembayaran_badge = 'badge-warning';
                            break;
                    }
                    ?>
                    <p><strong>Status Pembayaran:</strong> <span class="badge <?= $status_pembayaran_badge ?> py-2 px-3"><?= esc(ucwords(str_replace('_', ' ', $status_pembayaran_text))) ?></span></p> 
                    <?php if ($pesanan['status_pembayaran'] === 'menunggu_konfirmasi' && !empty($pesanan['file_bukti_pembayaran'])) : ?>
                        <p><strong>Atas Nama Pengirim:</strong> <?= esc($pesanan['atas_nama_pengirim'] ?? '-') ?></p>
                        <p><strong>Bukti Pembayaran:</strong>
                            <a href="<?= site_url('admin/pesanan/lihat_bukti/' . $pesanan['id']) ?>" target="_blank" class="btn btn-sm btn-info ml-2">
                                <i class="fas fa-eye"></i> Lihat Bukti
                            </a>
                        </p>
                        <form action="<?= site_url('admin/pesanan/konfirmasi_pembayaran/' . $pesanan['id']) ?>" method="post" class="mt-2" onsubmit="return confirm('Anda yakin ingin menyetujui pembayaran ini?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Setujui Pembayaran
                            </button>
                        </form>
                    <?php elseif ($pesanan['status_pembayaran'] === 'lunas') : ?>
                        <div class="alert alert-success p-2">Pembayaran sudah Lunas.</div>
                    <?php elseif ($pesanan['status_pesanan'] === 'Menunggu Pembayaran' && $pesanan['status_pembayaran'] === 'belum_bayar') : ?>
                        <div class="alert alert-warning p-2">Menunggu pelanggan melakukan pembayaran dan konfirmasi.</div>
                    <?php endif; ?>
                       <?php if ($pesanan['status_pesanan'] !== 'Selesai' && $pesanan['status_pesanan'] !== 'Dibatalkan') : ?>
                        <hr>
                        <h5>Ubah Status Pesanan</h5>
                        <form action="<?= site_url('admin/pesanan/ubah_status/' . $pesanan['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <select name="new_status" class="form-control">
                                    <option value="Menunggu Konfirmasi" <?= $pesanan['status_pesanan'] == 'Menunggu Konfirmasi' ? 'selected' : '' ?>>Menunggu Konfirmasi</option>
                                    <option value="Pembayaran Diterima" <?= $pesanan['status_pesanan'] == 'Pembayaran Diterima' ? 'selected' : '' ?>>Pembayaran Diterima</option>
                                    <option value="Diproses" <?= $pesanan['status_pesanan'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                                    <option value="Dikemas" <?= $pesanan['status_pesanan'] == 'Dikemas' ? 'selected' : '' ?>>Dikemas</option>
                                    <option value="Dikirim" <?= $pesanan['status_pesanan'] == 'Dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                    <option value="Selesai" <?= $pesanan['status_pesanan'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                    <option value="Tertunda" <?= $pesanan['status_pesanan'] == 'Tertunda' ? 'selected' : '' ?>>Tertunda</option>
                                    <option value="Dibatalkan" <?= $pesanan['status_pesanan'] == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm mt-2" onclick="return confirm('Anda yakin ingin mengubah status pesanan ini?');">
                                <i class="fas fa-save"></i> Simpan Status
                            </button>
                        </form>
                    <?php endif; ?>                                                                                                                                                                                                                                                                                                                                                                                                 </div>
                </div>
             </div>
         </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Pelanggan</h6>
        </div>
        <div class="card-body">
            <p><strong>Nama Pelanggan:</strong> <?= esc($pesanan['nama_pelanggan']) ?></p>
            <p><strong>Email:</strong> <?= esc($pesanan['email_pelanggan']) ?></p>
            <p><strong>Telepon:</strong> <?= esc($pesanan['telepon_pelanggan'] ?? '-') ?></p>
            <p><strong>Alamat:</strong> <?= esc($pesanan['alamat_pelanggan'] ?? '-') ?></p>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Item Pesanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Gambar</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detail_item_pesanan as $key => $item) : ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= esc($item['nama_produk']) ?></td>
                                <td><img src="<?= base_url('uploads/' . $item['gambar']) ?>" alt="<?= esc($item['nama_produk']) ?>" width="80"></td>
                                <td><?= esc($item['jumlah']) ?></td>
                                <td>Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($item['jumlah'] * $item['harga_satuan'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <a href="<?= site_url('admin/pesanan') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
    </a>

</div>
<?= $this->endSection() ?>