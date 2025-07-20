<?php

namespace App\Controllers\admin;

use App\Models\PenggunaanModel;
use App\Controllers\admin\BaseController;

class Penggunaanctrl extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $model = new PenggunaanModel();
        $data['penggunaan'] = $model->findAll();

        // Dapatkan nama rute dari URL
        $currentRoute = current_url();
        $routeSegments = explode('/', $currentRoute);
        $routeName = end($routeSegments); // mengambil bagian terakhir dari URL
        
        return $this->render('admin/penggunaan/index', [
            'routeName' => $routeName,
            'penggunaan' => $data['penggunaan']
        ]);
    }

    public function simpan()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $model = new PenggunaanModel();
        
        // Validasi input
        if (!$this->validate([
            'penggunaan' => 'required'
        ])) {
            return redirect()->back()->with('error', 'Data gagal ditambahkan.');
        }

        //format penulisan menggunakan Capitalize Each Word
        $penggunaan = ucwords(strtolower($this->request->getPost('penggunaan')));

        // Simpan data ke database
        $model->insert([
            'penggunaan' => $penggunaan
        ]);

        return redirect()->to(base_url('admin/dataPenggunaanTanah'))->with('message', 'Data berhasil ditambahkan!');
    }

    public function hapus($id_penggunaan)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $model = new PenggunaanModel();
        $id = $id_penggunaan;
        
        // Cek apakah data ada
        $data = $model->find($id);
        if (!$data) {
            return redirect()->to(base_url('admin/dataPenggunaanTanah'))->with('error', 'Data tidak ditemukan!');
        }

        // Hapus data dari database
        $model->delete($id);
        return redirect()->to(base_url('admin/dataPenggunaanTanah'))->with('message', 'Data berhasil dihapus!');
    }
}