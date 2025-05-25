<?php

namespace App\Controllers;

use App\Models\BungaModel;

class Cart extends BaseController
{
    protected $bungaModel;
    protected $session;

    public function __construct()
    {
        $this->bungaModel = new BungaModel();
        $this->session = session();
    }

    public function index()
    {
        $data['title'] = 'Keranjang Belanja';
        $data['cart'] = $this->session->get('cart');
        return view('cart/index', $data);
    }

    public function tambah()
    {
        $id = $this->request->getPost('id');
        $qty = $this->request->getPost('qty');

        $bunga = $this->bungaModel->find($id);

        if ($bunga && $qty > 0 && $qty <= $bunga['jumlah']) {
            $item = [
                'id' => $bunga['id'],
                'nama' => $bunga['nama'],
                'harga' => $bunga['harga'],
                'qty' => $qty,
                'gambar' => $bunga['gambar'],
            ];

            $cart = $this->session->get('cart');
            if (empty($cart)) {
                $cart[$id] = $item;
            } else {
                if (array_key_exists($id, $cart)) {
                    $cart[$id]['qty'] += $qty;
                     if($cart[$id]['qty'] > $bunga['jumlah']){
                         $this->session->setFlashdata('error', 'Jumlah melebihi stok yang tersedia.');
                         return redirect()->to('produk/' . $bunga['slug']);
                     }
                } else {
                    $cart[$id] = $item;
                }
            }

            $this->session->set('cart', $cart);
            $this->session->setFlashdata('success', 'Produk berhasil ditambahkan ke keranjang.');
            return redirect()->to('cart');
        } else {
            $this->session->setFlashdata('error', 'Produk tidak ditemukan atau jumlah tidak valid.');
            return redirect()->to('produk');
        }
    }

    public function updateAllItems()
    {
        $quantities = $this->request->getPost('qty'); // Ini adalah array: ['item_id1' => qty1, 'item_id2' => qty2]
        $cart = $this->session->get('cart');
 if (empty($cart) || !is_array($quantities) || empty($quantities)) {
            return redirect()->to('cart');
        }

        $updated = false;
        foreach ($quantities as $itemId => $newQty) {
            $newQty = (int)$newQty;

            if (isset($cart[$itemId])) {
                $bunga = $this->bungaModel->find($itemId);
                if ($bunga) {
                    if ($newQty <= 0) {
                        // Jika jumlah 0 atau kurang, hapus item (opsional, atau set ke 1)
                        unset($cart[$itemId]);
                        $updated = true;
                    } elseif ($newQty > $bunga['jumlah']) {
                        // Jika jumlah melebihi stok, set ke stok maksimal
                        $cart[$itemId]['qty'] = $bunga['jumlah'];
                        $this->session->setFlashdata('warning', "Jumlah untuk '" . esc($bunga['nama']) . "' melebihi stok. Disesuaikan menjadi " . $bunga['jumlah'] . ".");
                        $updated = true;
                    } elseif ($cart[$itemId]['qty'] != $newQty) {
                        $cart[$itemId]['qty'] = $newQty;
                        $updated = true;
                    }
                }
            }
        }

        if ($updated) {
            $this->session->set('cart', $cart);
            $this->session->setFlashdata('success', 'Keranjang berhasil diupdate.');
        }

        return redirect()->to('cart');
    }


    

    public function hapus($id)
    {
        $cart = $this->session->get('cart');
        if (array_key_exists($id, $cart)) {
            unset($cart[$id]);
            $this->session->set('cart', $cart);
            $this->session->setFlashdata('success', 'Produk berhasil dihapus dari keranjang.');
        }
        return redirect()->to('cart');
    }

    public function hapus_semua()
    {
        $this->session->remove('cart');
        $this->session->setFlashdata('success', 'Keranjang berhasil dikosongkan.');
        return redirect()->to('produk');
    }
}
