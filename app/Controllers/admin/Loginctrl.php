<?php

namespace App\Controllers\admin;

use App\Controllers\admin\BaseController;
use App\Models\AdminModel;

class Loginctrl extends BaseController
{
    public function login()
    {
        $currentRoute = current_url(); // memberikan nama pada URL
        $routeName = explode('/', $currentRoute); // Memberi URL untuk mendapatkan bagian rutenya
        // Jika sudah login, arahkan ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('admin/dashboard'));
        }
        return $this->render('admin/login/index', ['routeName' => end($routeName)]);
    }

    public function processLogin()
    {
        $session = session();
        $adminModel = new AdminModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan username
        $admin = $adminModel->where('username', $username)->first();     

        if (!$admin || !password_verify($password, $admin['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        if ($admin && $admin['deleted_at'] !== null) {
            return redirect()->back()->with('error', 'Akun telah dihapus.');
        }   

        // Cek status pengguna aktif atau tidak aktif
        if ($admin['status'] === 'inactive') {
            return redirect()->back()->withInput()->with('error', 'Akun Anda tidak aktif.');
        }

        // pengecekan pada saat login
        $session->set([
            'id_user'   => $admin['id_user'],
            'nama'      => $admin['nama'],
            'username'  => $admin['username'],
            'role'      => $admin['role'],
            'logged_in' => true,
        ]);

        // Redirect berdasarkan role
        if ($admin['role'] === 'pegawai') {
            return redirect()->to(base_url('admin/dashboard')); // Pegawai langsung ke halaman dashboard
        }
        return redirect()->to(base_url('admin/dashboard')); // sekretaris tetap ke dashboard
    }

    public function logout()
    {
        // Hapus session
        session()->destroy();
        return redirect()->to(base_url('/gate/login'));
    }
}
