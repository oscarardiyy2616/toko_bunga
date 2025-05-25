<?php

namespace App\Controllers;
use App\Models\PelangganModel; // Tambahkan ini

use App\Controllers\BaseController;

class Auth extends BaseController
{
    // ===========================
    // CUSTOMER LOGIN / LOGOUT
    // ===========================

    public function login()
    {
        return view('auth/customer_login'); // Buat view ini sesuai kebutuhan
    }

    public function attemptCustomerLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Misalnya model Pelanggan
        $user = model('PelangganModel')->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'isCustomer' => true,
                'customer_id' => $user['id'],
                'customer_name' => $user['nama']
            ]);
            return redirect()->to('/'); // Redirect ke halaman utama customer
        }

        return redirect()->back()->with('error', 'Email atau password salah');
    }

    public function customerLogout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

      public function register(): string
 {
     $data['title'] = 'Register Pelanggan';
     return view('auth/register', $data);
 }
 public function save()
 {
     $data = $this->request->getPost();
     if ($this->pelangganModel->save($data)) {
         $this->session->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
         return redirect()->to('/login');
     } else {
         $this->session->setFlashdata('errors', $this->pelangganModel->errors());
         return redirect()->to('/register')->withInput();
     }
 }                                                                                                                                                                                                                       // ===========================
    // ADMIN LOGIN / LOGOUT
    // ===========================

    public function adminLogin()
    {
        return view('admin/login'); // Buat view ini sesuai kebutuhan
    }

    public function attemptLogin()
    {
        $session = session();
        $model = new \App\Models\AdminModel();

        // Ambil nilai dari input form yang bernama 'username' (yang berisi email)
        $email_input = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Cari admin berdasarkan kolom 'email' di database dengan nilai dari $email_input
        $admin = $model->where('email', $email_input)->first();

        if ($admin) {
            if (password_verify($password, $admin['password'])) {
                $session->set([
                    'admin_id' => $admin['id'],
                    'admin_name' => $admin['nama'],
                    'logged_in' => true,
                    'isAdmin' => true // Sesuaikan key session menjadi 'isAdmin'
                ]);
                return redirect()->to('/admin'); // Ubah redirect ke /admin
            } else {
                return redirect()->to('/admin/login')->with('error', 'Email atau Password salah.');
            }
        } else {
            return redirect()->to('/admin/login')->with('error', 'Email atau Password salah.');
        }
    }


    public function logout()
    {
        $session = session(); // Inisialisasi helper session
        $session->remove('isAdmin'); // Key yang digunakan di constructor Admin dan di set saat login admin
        $session->remove('admin_id'); // Key yang di set saat login admin
        $session->remove('admin_name'); // Key yang di set saat login admin
        $session->remove('logged_in'); // Key ini juga di-set saat attemptLogin admin

        return redirect()->to('/admin/login')->with('message', 'Anda telah berhasil logout.');
    }
}
