<?php

namespace App\Controllers\admin;

use App\Controllers\admin\BaseController;
use App\Models\AdminModel;
use App\Models\SliderModel;
use App\Models\TanahModel;

class Dashboardctrl extends BaseController
{
    protected $TanahModel;
    protected $SliderModel;
    protected $AdminModel;

    public function __construct() {
        $this->TanahModel = new TanahModel();
        $this->SliderModel = new SliderModel();
        $this->AdminModel = new AdminModel();
    }

    public function index() 
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $userModel = new AdminModel();
        $userId = session()->get("id_user");
        $user = $userModel->find($userId);
        $jumlahUsers = $userModel->countUsers();

        $jumlahTanah = $this->TanahModel->countTanah();

        $rataRataHargaByWilayah = $this->TanahModel->getAverageHargaByWilayah();

        $hargaTertinggiKelurahan = $this->TanahModel->getHargaTertinggiByKelurahan();
        $hargaTertinggiKecamatan = $this->TanahModel->getHargaTertinggiByKecamatan();

        $top5HargaTertinggi = $this->TanahModel->getTop5HargaTertinggi();
        $top5HargaTerendah = $this->TanahModel->getTop5HargaTerendah();

        $currentRoute = current_url();
        $routeName = explode('/', $currentRoute);

        // Menggunakan metode render dari BaseController
        return $this->render('admin/dashboard/index', [
            'routeName' => end($routeName),
            'user' => $user,
            'jumlahUsers' => $jumlahUsers,
            'jumlahTanah' => $jumlahTanah,
            'rataRataHargaByWilayah' => $rataRataHargaByWilayah,
            'hargaTertinggiKelurahan' => $hargaTertinggiKelurahan,
            'hargaTertinggiKecamatan' => $hargaTertinggiKecamatan,
            'top5HargaTertinggi' => $top5HargaTertinggi,
            'top5HargaTerendah' => $top5HargaTerendah
        ]);
    }
}