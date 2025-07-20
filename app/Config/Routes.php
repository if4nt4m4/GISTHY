<?php

namespace Config;

use CodeIgniter\Commands\Utilities\Routes;
use CodeIgniter\Router\RouteCollection;

$routes = Services::routes();

/**
 * @var RouteCollection $routes
 */


$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Homectrl');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

//INTERNAL
$routes->get('/gate/login', 'Admin\Loginctrl::login'); // Root URL diarahkan ke login
$routes->post('/loginctrl/processLogin', 'Admin\Loginctrl::processLogin'); // Proses login
$routes->get('logout', 'Admin\Loginctrl::logout'); // Logout

$routes->get('admin/dashboard', 'admin\Dashboardctrl::index');

$routes->get('admin/peta', 'admin\Petactrl::index');
$routes->get('admin/dataPeta', 'admin\Petactrl::table');
$routes->get('admin/tambahDataPeta', 'admin\Petactrl::tambah');
$routes->post('admin/simpan', 'admin\Petactrl::simpan');
$routes->get('admin/tambahTitikKoordinat', 'admin\Petactrl::peta');
$routes->post('admin/generatePolygon/(:num)', 'admin\Petactrl::generatePolygon/$1');
$routes->post('admin/simpanKoordinat', 'admin\Petactrl::simpanKoordinat');
$routes->get('admin/editDataPeta/(:num)', 'admin\Petactrl::edit/$1'); 
$routes->get('admin/editHargaData/(:num)', 'admin\Petactrl::editHarga/$1'); 
$routes->post('admin/update/(:num)', 'admin\Petactrl::update/$1'); 
$routes->get('/admin/editKoordinat/(:num)', 'admin\Petactrl::editKoordinat/$1');
$routes->post('/admin/updateKoordinat/(:num)', 'admin\Petactrl::updateKoordinat/$1');
$routes->get('admin/resetKoordinat/(:num)', 'admin\Petactrl::resetKoordinat/$1');
$routes->get('/admin/hapusDataPeta/(:num)', 'admin\Petactrl::delete/$1');
$routes->post('admin/updateHarga', 'admin\Petactrl::updateHarga');

$routes->get('admin/dataPengguna', 'admin\Userctrl::index');
$routes->post('admin/simpanDataPengguna', 'admin\Userctrl::simpanPengguna');
$routes->post('admin/editDataPengguna', 'admin\Userctrl::updatePengguna');
$routes->get('admin/dataPengguna/delete/(:num)', 'admin\Userctrl::delete/$1');
$routes->post('admin/updateProfile', 'admin\Userctrl::updateProfile');
$routes->post('admin/changePassword', 'admin\Userctrl::changePassword');

$routes->get('admin/dataSlider', 'admin\Sliderctrl::index');
$routes->post('admin/simpanDataSlider', 'admin\Sliderctrl::simpan');
$routes->post('admin/editDataSlider', 'admin\Sliderctrl::upload');
$routes->get('admin/hapusDataSlider/(:num)', 'admin\Sliderctrl::delete/$1');

$routes->get('admin/dataPerusahaan', 'admin\ProfilPerusahaanctrl::index');
$routes->post('admin/simpanDataPerusahaan', 'admin\ProfilPerusahaanctrl::simpan');
$routes->post('admin/uploadLogo', 'admin\ProfilPerusahaanctrl::updateLogo');
$routes->post('admin/uploadGambar', 'admin\ProfilPerusahaanctrl::updateGambar');

$routes->get('admin/dataPenggunaanTanah', 'admin\Penggunaanctrl::index');
$routes->post('admin/simpanDataPenggunaan', 'admin\Penggunaanctrl::simpan');
$routes->get('admin/hapusDataPenggunaan/(:num)', 'admin\Penggunaanctrl::hapus/$1');

$routes->get('admin/riwayatHarga', 'admin\RiwayatHargactrl::index');
$routes->get('admin/riwayatHargaKelurahan', 'admin\RiwayatHargactrl::show');

$routes->get('admin/searchPeta', 'admin\Petactrl::search');
$routes->get('admin/exportPeta', 'admin\Petactrl::exportToExcel');
$routes->post('admin/importPeta', 'admin\Petactrl::importFromExcel');

//USER
// start frond end routes
$routes->get('/', 'user\Homectrl::index');

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}