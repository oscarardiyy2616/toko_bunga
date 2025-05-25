<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
    <h1>Keranjang Belanja</h1>

    <?php if (session()->get('success')) : ?>
        <div class="alert alert-success"><?= session()->get('success') ?></div>
    <?php endif; ?>

    <?php if (empty($cart) || count($cart) == 0) : ?>
        <p>Keranjang belanja Anda kosong.</p>
        <a href="<?= site_url('produk') ?>" class="btn btn-primary">Lanjut Belanja</a>
    <?php else : ?>
        <form action="<?= site_url('cart/update') ?>" method="post">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Gambar</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total= 0; ?>
                    <?php $no = 1; ?>
                    <?php foreach ($cart as $id => $item) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $item['nama'] ?></td>
                            <td><img src="<?= base_url('uploads/' . $item['gambar']) ?>" alt="<?= $item['nama'] ?>" width="100"></td>
                            <td>
                                <input type="number" name="qty[<?= $id ?>]" value="<?= $item['qty'] ?>" min="1" class="form-control" style="width: 80px;">
                            </td>
                            <td>Rp <?= number_format($item['harga']) ?></td>
                            <td>Rp <?= number_format($item['harga'] * $item['qty']) ?></td>
                            <td>
                                <a href="<?= site_url('cart/hapus/' . $id) ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin?')">Hapus</a>
                            </td>
                        </tr>
                        <?php $total += $item['harga'] * $item['qty']; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">Total</th>
                        <th>Rp <?= number_format($total) ?></th>
                        <th>
                            <button type="submit" class="btn btn-primary">Update Keranjang</button>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </form>

        <a href="<?= site_url('pesanan/checkout') ?>" class="btn btn-success">Checkout</a>
        <a href="<?= site_url('cart/hapus') ?>" class="btn btn-warning" onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')">Kosongkan Keranjang</a>
        <a href="<?= site_url('produk') ?>" class="btn btn-primary">Lanjut Belanja</a>
    <?php endif; ?>
<?= $this->endSection() ?>
