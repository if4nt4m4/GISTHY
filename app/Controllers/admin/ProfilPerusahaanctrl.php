<?php

namespace App\Controllers\admin;

use App\Controllers\admin\BaseController;
use App\Models\ProfilPerusahaanModel;

class ProfilPerusahaanctrl extends BaseController
{
    public function index() 
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $Profil = new ProfilPerusahaanModel();
        $profil = $Profil->findAll();

        $currentRoute = current_url();
        $routeName = explode('/', $currentRoute);

        return $this->render('admin/profilperusahaan/index', [
            'routeName' => end($routeName),
            'profil' => $profil
        ]);
    }

    public function simpan()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $id_user = session()->get('id_user');
        if (!$id_user) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu');
        }

        $Profil = new ProfilPerusahaanModel();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_perusahaan' => 'required',
            'deskripsi' => 'required',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $id_profil = $this->request->getPost('id_profil_perusahaan');

        $profilData = [
            'id_user' => $id_user,
            'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        if ($id_profil) {
            if ($Profil->update($id_profil, $profilData)) {
                return redirect()->to('/admin/dataPerusahaan')->with('message', 'Data berhasil diperbarui!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data, coba lagi.');
            }
        } else {
            return redirect()->to('/admin/dataPerusahaan')->with('message', 'Data Gagal Ditambahkan');
        }
    }

    public function updateLogo()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $id_user = session()->get('id_user');
        if (!$id_user) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu');
        }

        $Profil = new ProfilPerusahaanModel();
        $id_profil = $this->request->getPost('id_profil_perusahaan');
        
        if (!$id_profil) {
            return redirect()->back()->with('error', 'ID Profil tidak ditemukan.');
        }
        
        $file = $this->request->getFile('logo');
        
        // Periksa apakah file ada dan valid
        if (!$file) {
            return redirect()->back()->with('error', 'File logo tidak ditemukan.');
        }
        
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            if ($file->move('assets/img/', $newName)) {
                $Profil->update($id_profil, ['logo' => $newName]);
                return redirect()->to('/admin/dataPerusahaan')->with('message', 'Logo berhasil diperbarui!');
            }
        }
        
        return redirect()->back()->with('error', 'Gagal mengunggah logo. Pastikan file valid dan belum dipindahkan.');
    }

    public function updateGambar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $id_user = session()->get('id_user');
        if (!$id_user) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu');
        }

        $Profil = new ProfilPerusahaanModel();
        $id_profil = $this->request->getPost('id_profil_perusahaan');
        
        if (!$id_profil) {
            return redirect()->back()->with('error', 'ID Profil tidak ditemukan.');
        }
        
        $file = $this->request->getFile('gambar_about');
        
        // Periksa apakah file ada dan valid
        if (!$file) {
            return redirect()->back()->with('error', 'File gambar tidak ditemukan.');
        }
        
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            if ($file->move('assets/img/', $newName)) {
                $Profil->update($id_profil, ['gambar_about' => $newName]);
                return redirect()->to('/admin/dataPerusahaan')->with('message', 'Gambar About berhasil diperbarui!');
            }
        }
        
        return redirect()->back()->with('error', 'Gagal mengunggah gambar. Pastikan file valid dan belum dipindahkan.');
    }
}