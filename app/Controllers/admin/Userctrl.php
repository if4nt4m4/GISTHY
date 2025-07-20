<?php

namespace App\Controllers\admin;

use App\Models\AdminModel;
use App\Controllers\admin\BaseController;

class Userctrl extends BaseController
{
    public function index() 
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $adminModel = new AdminModel();
        $enum_statuses = $adminModel->getEnumValues('status');
        $enum_roles = $adminModel->getEnumValues("role");
    
        $users = $adminModel->findAll();
    
        // Dapatkan nama rute dari URL
        $currentRoute = current_url();
        $routeSegments = explode('/', $currentRoute);
        $routeName = end($routeSegments); // Ambil bagian terakhir dari URL
    
        return $this->render('admin/users/index', [
            'users' => $users,
            'routeName' => $routeName, // Kirim ke view
            'enum_statuses' => $enum_statuses,
            'enum_roles' => $enum_roles
        ]);
    }    

    public function simpanPengguna()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $adminModel = new AdminModel();
        $allowed_status = $adminModel->getEnumValues('status');
        $allowed_role = $adminModel->getEnumValues('role');

        $id = $this->request->getVar('id_user');
        $nama = $this->request->getVar('nama');
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $status = $this->request->getVar('status');
        $role = $this->request->getVar('role');

        // Validasi status dan role
        if (!in_array($status, $allowed_status)) {
            return redirect()->back()->withInput()->with('error', 'Status tidak valid.');
        }
        if (!in_array($role, $allowed_role)) {
            return redirect()->back()->withInput()->with('error', 'Role tidak valid.');
        }

        // Cek apakah username sudah ada
        $existingUser = $adminModel->where('username', $username)->first();
        if (!$id && $existingUser) { // Cek hanya untuk insert
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
        }

        // Siapkan data untuk disimpan
        $data = [
            'nama' => $nama,
            'username' => $this->request->getVar('username'),
            'status' => $status,
            'role' => $role,
            'gambar' => 'Coba.png'
        ];

        if ($id) {
            $password = $this->request->getVar('password');
            if (!empty($password)) {
                $data['password'] = $password;
            }
            // Update data pengguna jika ID diberikan
            $adminModel->update($id, $data);
        return redirect()->to(base_url('admin/dataPengguna'))->with('message', 'Data berhasil diperbarui');
        } else {
            $data['password'] = $password;
            // Insert data baru
            $adminModel->insert($data);
        return redirect()->to(base_url('admin/dataPengguna'))->with('message', 'Data berhasil ditambahkan');
        }
    }

    public function updatePengguna()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $id_user = $this->request->getPost('id_user');
        $status = $this->request->getPost('status');
        $role = $this->request->getPost('role');

        // Validasi apakah ID ada
        if (!$id_user) {
            return redirect()->back()->with('error', 'User ID tidak ditemukan.');
        }

        $adminModel = new AdminModel();
        $adminModel->update($id_user, [
            'status' => $status,
            'role'   => $role
        ]);

        return redirect()->to(base_url('admin/dataPengguna'))->with('message', 'Data pengguna berhasil diperbarui.');
    }

    public function delete($id_user = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!$id_user) {
            return redirect()->back()->with('error', 'User ID tidak valid.');
        }
        
        $adminModel = new AdminModel();
        if (!$adminModel->find($id_user)) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $adminModel->delete($id_user);
        return redirect()->to(base_url('admin/dataPengguna'))->with('message', 'Data berhasil dihapus.');
    }

    public function updateProfile()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $userModel = new AdminModel();
        $id = $this->request->getPost('id_user');
        $nama = $this->request->getPost('nama');
        $username = $this->request->getPost('username');
        $current_password = $this->request->getPost('current_password');

        // Validasi password saat ini
        $user = $userModel->find($id);
        if (!password_verify($current_password, $user['password'])) {
            return redirect()->back()->with('profile_error', 'Password saat ini salah!');
        }

        // Siapkan data untuk diupdate
        $data = [
            'nama' => $nama,
            'username' => $username
        ];

        // Handle upload gambar hanya jika ada file yang diunggah
        $gambar = $this->request->getFile('gambar');
        if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
            // Validasi ukuran file (maks 2MB)
            if ($gambar->getSize() > 2097152) {
                return redirect()->back()->with('profile_error', 'Ukuran file maksimal 2MB');
            }

            // Generate nama file baru
            $namaGambar = $gambar->getRandomName();
            $gambar->move('assets-admin/img/', $namaGambar);
            
            // Hapus gambar lama jika bukan gambar default
            if ($user['gambar'] && $user['gambar'] != 'default.png') {
                $oldImagePath = 'assets-admin/img/' . $user['gambar'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $data['gambar'] = $namaGambar;
        }

        $userModel->update($id, $data);

        // Update session jika user yang diupdate adalah user yang sedang login
        if (session()->get('id_user') == $id) {
            $updatedUser = $userModel->find($id);
            session()->set([
                'nama' => $updatedUser['nama'],
                'username' => $updatedUser['username'],
                'gambar' => $updatedUser['gambar']
            ]);
        }

        return redirect()->back()->with('profile_success', 'Profil berhasil diperbarui!');
    }

    public function changePassword()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $userModel = new AdminModel();
        $id = $this->request->getPost('id_user');
        $passwordBaru = $this->request->getPost('password_baru');
        $konfirmasiPassword = $this->request->getPost('konfirmasi_password');

        // Ambil data user berdasarkan ID
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan!');
        }

        // Periksa apakah password baru sama dengan password lama
        if (password_verify($passwordBaru, $user['password'])) {
            return redirect()->back()->with('password_error', 'Password baru tidak boleh sama dengan password lama!');
        }

        // Periksa apakah password baru cocok dengan konfirmasi password
        if ($passwordBaru !== $konfirmasiPassword) {
            return redirect()->back()->with('password_error', 'Konfirmasi password tidak cocok!');
        }
        
        // Update password - use save() instead of update() to ensure proper handling
        $userModel->update($id, ['password' => $passwordBaru]);

        return redirect()->back()->with('password_success', 'Password berhasil diubah!');
    }
}

