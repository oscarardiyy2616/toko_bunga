<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminGuest implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        // Periksa apakah sesi 'admin_logged_in' ada dan bernilai true
        if ($session->has('admin_logged_in') && $session->get('admin_logged_in') === true) {
            // Jika admin sudah login, arahkan ke dashboard admin
            return redirect()->to('admin/dashboard'); // Pastikan rute ini benar
        }
        // Jika admin belum login, izinkan request dilanjutkan (tidak melakukan apa-apa)
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah request
    }
}