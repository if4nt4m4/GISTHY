<?php

namespace App\Controllers\admin;

use App\Controllers\admin\BaseController;
use App\Models\SliderModel;

class Sliderctrl extends BaseController
{
    public function index() 
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $sliderModel = new SliderModel();
        $currentRoute = current_url(); // Mendapatkan URL lengkap
        $routeName = explode('/', $currentRoute); // Memecah URL untuk mendapatkan bagian rutenya
        $sliders = $sliderModel->findAll();

        // Mengoper data ke view
        return $this->render('admin/slider/index', [
            'routeName' => end($routeName),
            'sliders' => $sliders
        ]);
    }

    public function simpan()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $sliderModel = new SliderModel();

        // Validasi File Upload
        $file = $this->request->getFile('gambar');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('assets/img', $newName); // Simpan ke folder assets/slider
        } else {
            return redirect()->back()->with('error', 'Gagal mengunggah gambar!');
        }

        // Simpan ke database
        $sliderModel->insert([
            'gambar' => $newName
        ]);

        return redirect()->to(base_url('admin/dataSlider'))->with('message', 'Slider berhasil ditambahkan!');
    }

    public function upload()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $sliderModel = new SliderModel();
        $id = $this->request->getPost('id_slider');

        $slider = $sliderModel->find($id);
        if (!$slider) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        // Jika ada gambar baru diunggah
        $file = $this->request->getFile('gambar');
        if ($file->isValid() && !$file->hasMoved()) {
            // Hapus gambar lama
            if ($slider['gambar'] && file_exists('assets/img/' . $slider['gambar'])) {
                unlink('assets/img/' . $slider['gambar']);
            }

            $newName = $file->getRandomName();
            $file->move('assets/img', $newName);
            $sliderModel->update($id, ['gambar' => $newName]);
        }

        return redirect()->to(base_url('admin/dataSlider'))->with('message', 'Slider berhasil diperbarui!');
    }

    public function delete($id_slider)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $sliderModel = new SliderModel();
        $slider = $sliderModel->find($id_slider);

        if (!$slider) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        // Hapus gambar dari folder
        if ($slider['gambar'] && file_exists('assets/img/' . $slider['gambar'])) {
            unlink('assets/img/' . $slider['gambar']);
        }

        // Hapus data dari database
        $sliderModel->delete($id_slider);

        return redirect()->to(base_url('admin/dataSlider'))->with('message', 'Slider berhasil dihapus!');
    }
}