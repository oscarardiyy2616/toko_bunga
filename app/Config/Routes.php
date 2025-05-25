<?php

namespace Config;

use App\Controllers\Home;
use App\Controllers\Produk;
use App\Controllers\Auth;
use App\Controllers\Admin;
use App\Controllers\Pesanan;
use App\Controllers\Kontak;

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Admin');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Halaman Utama
$routes->get('/', [Home::class, 'index']);

// Produk
$routes->get('produk', [Produk::class, 'index']);
$routes->get('produk/kategori/(:num)', [Produk::class, 'kategori']);
$routes->get('produk/detail/(:segment)', [Produk::class, 'detail']); // Rute baru untuk produk/detail/slug
$routes->get('produk/(:segment)', [Produk::class, 'detail']); // Rute untuk produk/slug (jika masih digunakan)
// Pelanggan
$routes->get('login', [Auth::class, 'login'], ['filter' => 'customerGuest']);
$routes->post('login', [Auth::class, 'attemptCustomerLogin'], ['filter' => 'customerGuest']);
$routes->get('logout', [Auth::class, 'customerLogout'], ['filter' => 'customerAuth']);
$routes->get('register', [Auth::class, 'register'], ['filter' => 'customerGuest']);
$routes->post('register', [Auth::class, 'register'], ['filter' => 'customerGuest']);



// Rute utama untuk area admin.
// Jika belum login, filter 'adminAuth' akan redirect ke '/admin/login'.
// Contoh jika controller Anda bernama AuthController dan methodnya adminLogin

// Atau jika controller Anda bernama AdminController dan methodnya login
// $routes->get('login/admin', 'AdminController::login');

// Atau jika controller Anda berada di dalam subfolder Admin, misalnya App\Controllers\Admin\AuthController
// $routes->get('login/admin', 'Admin\AuthController::login');

// Admin Login & Logout
$routes->get('admin/login', [Auth::class, 'adminLogin'], ['filter' => 'adminGuest']); // Menampilkan form login admin
$routes->post('admin/login', [Auth::class, 'attemptLogin'], ['filter' => 'adminGuest']); // Memproses login admin
$routes->get('admin/logout', [Auth::class, 'logout'], ['filter' => 'adminAuth']); // Logout admin

// Rute Area Admin (setelah login)
$routes->group('admin', ['filter' => 'adminAuth'], function ($routes) {
    $routes->get('/', 'Admin::index'); // Dashboard admin di /admin atau /admin/
    $routes->get('dashboard', 'Admin::index');

    // Produk
    $routes->get('produk', 'Admin::produk');
    $routes->get('produk/tambah', 'Admin::produk_tambah');
    $routes->post('produk/simpan', 'Admin::produk_simpan');
    $routes->get('produk/edit/(:num)', 'Admin::produk_edit/$1');
    $routes->post('produk/update/(:num)', 'Admin::produk_update/$1');
    $routes->get('produk/hapus/(:num)', 'Admin::produk_hapus/$1');

    // Kategori
    $routes->get('kategori', 'Admin::kategori');
    $routes->get('kategori/tambah', 'Admin::kategori_tambah');
    $routes->post('kategori/simpan', 'Admin::kategori_simpan');
    $routes->get('kategori/edit/(:num)', 'Admin::kategori_edit/$1');
    $routes->post('kategori/update/(:num)', 'Admin::kategori_update/$1');
    $routes->get('kategori/hapus/(:num)', 'Admin::kategori_hapus/$1');
// Pesanan (Baru)
    $routes->get('pesanan', 'Admin::pesanan'); 
    $routes->post('pesanan/konfirmasi_pembayaran/(:num)', 'Admin::konfirmasi_pembayaran/$1');
    $routes->get('pesanan/detail/(:num)', 'Admin::pesanan_detail/$1'); 
});


// Pesanan
$routes->get('pesanan', [Pesanan::class, 'index'], ['filter' => 'customerAuth']);
$routes->get('pesanan/detail/(:num)', [Pesanan::class, 'detail/$1'], ['filter' => 'customerAuth']);
$routes->get('pesanan/checkout', [Pesanan::class, 'checkout'], ['filter' => 'customerAuth']);
$routes->post('pesanan/proses_checkout', [Pesanan::class, 'proses_checkout'], ['filter' => 'customerAuth']);
$routes->post('pesanan/proses_pembayaran_palsu/(:num)', [Pesanan::class, 'proses_pembayaran_palsu/$1'], ['filter' => 'customerAuth']);
$routes->get('pesanan/bayar/(:num)', [Pesanan::class, 'bayar/$1'], ['filter' => 'customerAuth']);
$routes->get('pesanan/terima/(:num)', [Pesanan::class, 'terima/$1'], ['filter' => 'customerAuth']);

// Kontak
$routes->get('kontak', [Kontak::class, 'index']);
$routes->post('kontak/kirim', [Kontak::class, 'kirimPesan']);

// Cart
$routes->post('cart/tambah', 'Cart::tambah',['filter' => 'customerAuth']);
$routes->get('cart', 'Cart::index',['filter' => 'customerAuth']);
$routes->post('cart/update', 'Cart::updateAllItems',['filter' => 'customerAuth']); // Menggunakan method baru: updateAllItems
$routes->get('cart/hapus/(:num)', 'Cart::hapus/$1',['filter' => 'customerAuth']);
$routes->get('cart/hapus', 'Cart::hapus_semua',['filter' => 'customerAuth']);
