<?php

namespace App\Controllers\admin;

use App\Controllers\admin\BaseController;
use App\Models\PenggunaanModel;
use App\Models\TanahModel;
use App\Models\RiwayatHargaModel;
use App\Models\TitikKoordinatModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Petactrl extends BaseController
{
    protected $TanahModel;
    protected $RiwayatHargaModel;
    protected $TitikKoordinatModel;
    protected $PenggunaanModel;

    public function __construct()
    {
        $this->TanahModel = new TanahModel();
        $this->RiwayatHargaModel = new RiwayatHargaModel();
        $this->TitikKoordinatModel = new TitikKoordinatModel();
        $this->PenggunaanModel = new PenggunaanModel();
    }

    public function index() 
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        // mengambil data tanah dari database
        $tanahData = $this->TanahModel->getAll();
        $features = [];
        $hargaList = [];

        foreach ($tanahData as $tanah) {
            $coordinates = $this->TitikKoordinatModel->where('id_tanah', $tanah['id_tanah'])->findAll();
            
            if (!empty($coordinates)) {
                $polygonCoordinates = [];
                foreach ($coordinates as $coordinate) {
                    $polygonCoordinates[] = [(float)$coordinate['longitude'], (float)$coordinate['latitude']];
                }

                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [$polygonCoordinates],
                    ],
                    'properties' => [
                        'penggunaan' => $tanah['kawasan'],
                        'kecamatan' => $tanah['kecamatan'],
                        'wilayah' => $tanah['wilayah'],
                        'kelurahan' => $tanah['kelurahan'],
                        'waktu_terkini' => $tanah['waktu_terkini'],
                        'harga_terkini' => $tanah['harga_terkini']
                    ],
                ];

                $hargaList[] = $tanah['harga_terkini'];
            }
        }

        $hargaMin = !empty($hargaList) ? min($hargaList) : 0;
        $hargaMax = !empty($hargaList) ? max($hargaList) : 0;

        // mengambil titik referensi(objek/data pembanding tanah) dari kolom titik_latitude & titik_longitude di model Tanah
        $titikReferensi = [];
        foreach ($tanahData as $tanah) {
            if (!empty($tanah['titik_referensi']['titik_latitude']) && !empty($tanah['titik_referensi']['titik_longitude'])) {
                $titikReferensi[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            (float)$tanah['titik_referensi']['titik_longitude'], 
                            (float)$tanah['titik_referensi']['titik_latitude']
                        ]
                    ],
                    'properties' => [
                        'penggunaan' => $tanah['kawasan'],
                        'kecamatan' => $tanah['kecamatan'],
                        'wilayah' => $tanah['wilayah'],
                        'kelurahan' => $tanah['kelurahan'],
                        'waktu_terkini' => $tanah['waktu_terkini'],
                        'harga_terkini' => $tanah['harga_terkini'],
                        'indikasi' => $tanah['indikasi'], 
                        'luas_tanah' => $tanah['luas_tanah'],
                        'luas_bangunan' => $tanah['luas_bangunan'],
                        'nama_pemilik' => $tanah['nama_pemilik'],
                        'no_telp' => $tanah['no_telp'],
                        'keterangan' => 'Titik Referensi ' . $tanah['kelurahan'],
                    ]
                ];
            }
        }

        $currentRoute = current_url();
        $routeParts = explode('/', $currentRoute);
        $routeName = end($routeParts);

        return $this->render('admin/peta/index', [
            'routeName' => $routeName,
            'geoJsonData' => json_encode(['type' => 'FeatureCollection', 'features' => $features]),
            'titikReferensiData' => json_encode(['type' => 'FeatureCollection', 'features' => $titikReferensi]),
            'hargaMin' => $hargaMin,
            'hargaMax' => $hargaMax,
        ]);
    }

    public function table()
    {
        // pengecekan pengguna apakah sudah login atau belum
        if (!session()->has('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        // mengambil semua data tanah yang ada di database
        $tanahData = $this->TanahModel->getAll(); 

        // mengambil semua riwayat harga tanah (jika ada) setiap id_tanah/data tanah
        foreach ($tanahData as &$tanah) {
            if (isset($tanah['id_tanah']) && is_numeric($tanah['id_tanah'])) {
                $tanah['riwayat_harga'] = $this->RiwayatHargaModel->where('id_tanah', $tanah['id_tanah'])->findAll();
            } else {
                $tanah['riwayat_harga'] = [];
            }
        }

        // mengambil tanggal harga tanah dan tidak boleh = 0000-00-00
        $availableDates = $this->TanahModel->builder()
            ->select('DISTINCT(waktu_terkini) as tanggal')
            ->where('waktu_terkini !=', '0000-00-00')
            ->orderBy('waktu_terkini', 'DESC')
            ->get()
            ->getResultArray();

        // mengambil nama URL
        $currentRoute = current_url();
        $routeParts = explode('/', $currentRoute);
        $routeName = end($routeParts);

        return $this->render('admin/peta/tabel', [
            'availableDates' => array_column($availableDates, 'tanggal'),
            'routeName' => $routeName,
            'tanah' => $tanahData
        ]);
    }

    public function tambah()
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $currentRoute = current_url();
        $routeName = explode('/', $currentRoute);

        $data['penggunaan'] = $this->PenggunaanModel->findAll();

        // mengambil data koordinat dari session
        $koordinat = session()->get('koordinat') ?? [];

        return $this->render('admin/peta/tambah', [
            'routeName' => end($routeName),
            'koordinat' => $koordinat,
            'penggunaan' => $data['penggunaan']
        ]);
    }

    public function simpan()
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        // mengambil id_user dari session dengan key 'id'
        $id_user = session()->get('id_user');
        if (!$id_user) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu');
        }

        // Validasi data yang diinputkan
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kawasan' => 'required',
            'kecamatan' => 'required',
            'wilayah' => 'required',
            'kelurahan' => 'required',
            'harga_terkini' => 'required|numeric',
            'waktu_terkini' => 'required',
            'indikasi' => 'required|in_list[nilai pasar tanah, nilai tanah data pembanding, permeter tanah data pembanding]',
            'titik_latitude' => 'required',
            'titik_longitude' => 'required',
            'luas_tanah' => 'required|numeric',
            'luas_bangunan' => 'required|numeric',
            'no_telp' => 'required',
            'nama_pemilik' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        //format tulisan menggunakan Capitaize Each Word
        $kecamatan = ucwords(strtolower($this->request->getPost('kecamatan')));
        $wilayah   = ucwords(strtolower($this->request->getPost('wilayah')));
        $kelurahan = ucwords(strtolower($this->request->getPost('kelurahan')));

        // Simpan data tanah
        $tanahData = [
            'id_user' => $id_user, // mengambil id_user dari session dan disimpan ke dalam tabel tanah
            'kawasan' => $this->request->getPost('kawasan'),
            'kecamatan' => $kecamatan,
            'wilayah' => $wilayah,
            'kelurahan' => $kelurahan,
            'harga_terkini' => $this->request->getPost('harga_terkini'),
            'waktu_terkini' => $this->request->getPost('waktu_terkini'),
            'indikasi' => $this->request->getPost('indikasi'),
            'titik_latitude' => $this->request->getPost('titik_latitude'),
            'titik_longitude' => $this->request->getPost('titik_longitude'),
            'luas_tanah' => $this->request->getPost('luas_tanah'),
            'luas_bangunan' => $this->request->getPost('luas_bangunan'),
            'no_telp' => $this->request->getPost('no_telp'),
            'nama_pemilik' => $this->request->getPost('nama_pemilik'),
        ];
        $idTanah = $this->TanahModel->insert($tanahData);

        // Simpan data riwayat harga
        $riwayatHargaData = [
            'id_tanah' => $idTanah,
            'waktu' => '0000-00-00', // format tanggal default
            'harga' => 0.00,
        ];
        $this->RiwayatHargaModel->insert($riwayatHargaData);

        // Automatically generate polygon
        $this->generatePolygon($idTanah);        

        return redirect()->to('/admin/dataPeta')->with('message', 'Data berhasil disimpan!');
    }

    public function peta()
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $currentRoute = current_url();
        $routeName = explode('/', $currentRoute);

        return $this->render('admin/peta/titik_koordinat', ['routeName' => end($routeName)]);
    }

    // fungsi otomatisasi polygon 
    public function generatePolygon($id_tanah)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $tanah = $this->TanahModel->find($id_tanah);
        if (!$tanah) {
            return redirect()->back()->with('error', 'Data tanah tidak ditemukan.');
        }

        // mengambil titik latitude dan titik longitude yang diinput
        $lat = $tanah['titik_latitude'];
        $lng = $tanah['titik_longitude'];

        // mengecek apakah kedua titik koordinat valid atau tidak
        if ($lat == 0.00000 || $lng == 0.00000) {
            return redirect()->back()->with('error', 'Titik referensi tidak valid. Silakan set titik referensi terlebih dahulu.');
        }

        // membuat polygon berbentuk persegi dengan radius 100 meter
        $radius = 100 / 111320; // ≈ 0.000898 degrees (100 meters)
        $polygonPoints = [
            [$lat + $radius, $lng - $radius], // kiri atas
            [$lat + $radius, $lng + $radius], // kanan atas
            [$lat - $radius, $lng + $radius], // kanan bawah
            [$lat - $radius, $lng - $radius], // kiri bawah
            [$lat + $radius, $lng - $radius]  // penutup polygon
        ];

        // Koneksi ke database dan jalankan transaksi
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Array untuk menyimpan titik yang sudah diproses
            $processedPoints = [];
            
            // menyimpan titik koordinat baru
            foreach ($polygonPoints as $point) {
                // Format titik untuk memudahkan perbandingan
                $formattedPoint = [
                    'latitude' => number_format($point[0], 5, '.', ''),
                    'longitude' => number_format($point[1], 5, '.', '')
                ];
                
                // mengecek apakah titik sudah ada dalam array processedPoints
                $isDuplicate = false;
                foreach ($processedPoints as $processed) {
                    if ($processed['latitude'] === $formattedPoint['latitude'] && 
                        $processed['longitude'] === $formattedPoint['longitude']) {
                        $isDuplicate = true;
                        break;
                    }
                }
                
                // Jika bukan duplikat, simpan ke database dan tambahkan ke processedPoints
                if (!$isDuplicate) {
                    $this->TitikKoordinatModel->insert([
                        'id_tanah' => $id_tanah,
                        'latitude' => $formattedPoint['latitude'],
                        'longitude' => $formattedPoint['longitude']
                    ]);
                    
                    $processedPoints[] = $formattedPoint;
                }
            }
            
            $db->transComplete();
            
            return true; // Kembalikan true untuk indikasi sukses
        } catch (\Exception $e) {
            $db->transRollback();
            throw new \Exception('Gagal membuat polygon: '.$e->getMessage());
        }
    }

    public function simpanKoordinat($id_tanah)
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $id_user = session()->get('id_user');
        if (!$id_user) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu');
        }

        $latitudes = $this->request->getPost('latitude');
        $longitudes = $this->request->getPost('longitude');

        // memastikan data koordinat tidak kosong dan memiliki jumlah yang sama
        if (!$latitudes || !$longitudes || count($latitudes) !== count($longitudes)) {
            return redirect()->back()->with('error', 'Silakan pilih titik koordinat yang valid.');
        }

        // Validasi data numerik dan buat array unik
        $koordinatBaru = [];
        foreach ($latitudes as $index => $latitude) {
            $longitude = $longitudes[$index];

            if (!is_numeric($latitude) || !is_numeric($longitude)) {
                return redirect()->back()->with('error', 'Format koordinat tidak valid.');
            }

            // Gunakan array asosiatif dengan kunci unik untuk mencegah duplikasi
            $koordinatBaru[md5($latitude . ',' . $longitude)] = [
                'id_tanah' => $id_tanah,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];
        }

        // Koneksi ke database dan jalankan transaksi
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Hapus titik koordinat lama
            $this->TitikKoordinatModel->where('id_tanah', $id_tanah)->delete();

            // Simpan hanya titik koordinat unik ke database
            foreach ($koordinatBaru as $titikKoordinat) {
                $this->TitikKoordinatModel->insert($titikKoordinat);
            }

            // Commit transaksi
            $db->transCommit();

            // Simpan koordinat dalam session untuk referensi
            session()->set('koordinat', array_values($koordinatBaru));

            return redirect()->to('/admin/dataPeta')->with('message', 'Koordinat berhasil diperbarui!');
        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui koordinat.');
        }
    }

    public function edit($id_tanah)
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        // mengambil data tanah berdasarkan ID
        $tanah = $this->TanahModel->find($id_tanah);
        $penggunaan = $this->PenggunaanModel->findAll();
        if (!$tanah) {
            return redirect()->to('/admin/dataPeta')->with('error', 'Data tanah tidak ditemukan.');
        }

        // mengambil data riwayat harga terkait
        $riwayatHarga = $this->RiwayatHargaModel->where('id_tanah', $id_tanah)->findAll();

        // mengambil data titik koordinat terkait
        $titikKoordinat = $this->TitikKoordinatModel->where('id_tanah', $id_tanah)->findAll();

        $currentRoute = current_url();
        $routeName = explode('/', $currentRoute);

        return $this->render('admin/peta/edit', [
            'routeName' => end($routeName),
            'tanah' => $tanah,
            'riwayatHarga' => $riwayatHarga,
            'titikKoordinat' => $titikKoordinat,
            'penggunaan' => $penggunaan
        ]);
    }

    public function update($id_tanah)
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        // mengambil id_user dari session dengan key 'id'
        $id_user = session()->get('id_user');

        if (!$id_user) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu');
        }

        // Validasi data yang diinput
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kawasan' => 'required',
            'kecamatan' => 'required',
            'wilayah' => 'required',
            'kelurahan' => 'required',
            'indikasi' => 'required',
            'titik_latitude' => 'required',
            'titik_longitude' => 'required',
            'luas_tanah' => 'required|numeric',
            'luas_bangunan' => 'required|numeric',
            'no_telp' => 'required',
            'nama_pemilik' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        //format tulisan menggunakan Capitaize Each Word
        $kecamatan = ucwords(strtolower($this->request->getPost('kecamatan')));
        $wilayah   = ucwords(strtolower($this->request->getPost('wilayah')));
        $kelurahan = ucwords(strtolower($this->request->getPost('kelurahan')));

        // Dapatkan data lama untuk memeriksa perubahan koordinat
        $oldData = $this->TanahModel->find($id_tanah);
        $oldLat = $oldData['titik_latitude'];
        $oldLng = $oldData['titik_longitude'];
        
        // Data baru
        $newLat = $this->request->getPost('titik_latitude');
        $newLng = $this->request->getPost('titik_longitude');

        // Update data tanah tanpa harga_terkini dan waktu_terkini
        $tanahData = [
            'id_user' => $id_user, // Gunakan id_user dari session
            'kawasan' => $this->request->getPost('kawasan'),
            'kecamatan' => $kecamatan,
            'wilayah' => $wilayah,
            'kelurahan' => $kelurahan,
            'indikasi' => $this->request->getPost('indikasi'),
            'titik_latitude' => $newLat,
            'titik_longitude' => $newLng,
            'luas_tanah' => $this->request->getPost('luas_tanah'),
            'luas_bangunan' => $this->request->getPost('luas_bangunan'),
            'nama_pemilik' => $this->request->getPost('nama_pemilik'),
            'no_telp' => $this->request->getPost('no_telp'),
        ];
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Update data tanah
            $this->TanahModel->update($id_tanah, $tanahData);
            
            // Jika koordinat referensi berubah, update polygon
            if ($oldLat != $newLat || $oldLng != $newLng) {
                // Hapus semua titik koordinat lama
                $this->TitikKoordinatModel->where('id_tanah', $id_tanah)->delete();
                
                // Generate polygon baru berdasarkan koordinat referensi yang baru
                $this->generatePolygon($id_tanah);
            }
            
            $db->transComplete();
            
            // Redirect ke halaman dataPeta setelah berhasil memperbarui
            return redirect()->to('/admin/dataPeta')->with('message', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function editKoordinat($id_tanah)
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $id_user = session()->get('id_user');
        if (!$id_user) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu');
        }

        // mengambil data tanah berdasarkan ID
        $tanah = $this->TanahModel->find($id_tanah);
        if (!$tanah) {
            return redirect()->to('/admin/dataPeta')->with('error', 'Data tanah tidak ditemukan.');
        }

        // mengambil data titik koordinat terkait
        $titikKoordinat = $this->TitikKoordinatModel->where('id_tanah', $id_tanah)->findAll();

        $currentRoute = current_url();
        $routeName = explode('/', $currentRoute);

        return $this->render('admin/peta/titik_koordinat', [
            'routeName' => end($routeName),
            'editMode' => true,
            'id_tanah' => $id_tanah,
            'titikKoordinat' => $titikKoordinat,
            'tanah' => $tanah
        ]);
    }

    public function updateKoordinat($id_tanah)
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $id_user = session()->get('id_user');
        if (!$id_user) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu');
        }

        $latitudes = $this->request->getPost('latitude');
        $longitudes = $this->request->getPost('longitude');

        // memastikan data koordinat tidak kosong dan memiliki jumlah yang sama
        if (!$latitudes || !$longitudes || count($latitudes) !== count($longitudes)) {
            return redirect()->back()->with('error', 'Silakan pilih titik koordinat yang valid.');
        }

        // Validasi data numerik dan buat array unik
        $koordinatBaru = [];
        foreach ($latitudes as $index => $latitude) {
            $longitude = $longitudes[$index];

            if (!is_numeric($latitude) || !is_numeric($longitude)) {
                return redirect()->back()->with('error', 'Format koordinat tidak valid.');
            }

            // Gunakan array asosiatif dengan kunci unik untuk mencegah duplikasi
            $koordinatBaru[md5($latitude . ',' . $longitude)] = [
                'id_tanah' => $id_tanah,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];
        }

        // Koneksi ke database dan jalankan transaksi
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Hapus titik koordinat lama
            $this->TitikKoordinatModel->where('id_tanah', $id_tanah)->delete();

            // Simpan hanya titik koordinat unik ke database
            foreach ($koordinatBaru as $titikKoordinat) {
                $this->TitikKoordinatModel->insert($titikKoordinat);
            }

            // Commit transaksi
            $db->transCommit();

            // Simpan koordinat dalam session untuk referensi
            session()->set('koordinat', array_values($koordinatBaru));

            return redirect()->to('/admin/dataPeta')->with('message', 'Koordinat berhasil diperbarui!');
        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui koordinat.');
        }
    }

    public function resetKoordinat($id_tanah)
    {
        $this->TitikKoordinatModel->where('id_tanah', $id_tanah)->delete(); // Hapus koordinat dari database
        return redirect()->to(base_url('admin/editKoordinat/' . $id_tanah))->with('success', 'Koordinat berhasil direset.');
    }
    
    public function updateHarga()
    {
        $id_tanah = $this->request->getPost('id_tanah'); // Ambil ID tanah dari input

        // mengambil data tanah berdasarkan ID
        $tanah = $this->TanahModel->find($id_tanah);

        if (!$tanah) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tanah tidak ditemukan.']);
        }

        $id_user = session()->get('id_user');
        if (!$id_user) {
            return redirect()->to(base_url('/gate/login'))->with('error', 'Silakan login terlebih dahulu');
        }

        // mengambil nilai baru dari form input
        $hargaBaru = $this->request->getPost('harga_terkini');
        $waktuBaru = $this->request->getPost('waktu_terkini'); // Waktu saat ini

        // Update harga_terkini and waktu_terkini in the tanah table
        $update = $this->TanahModel->update($id_tanah, [
            'harga_terkini' => $hargaBaru,
            'waktu_terkini' => $waktuBaru
        ]);

        if ($update) {
            // menghapus data riwayat harga yang dibuat secara default
            $this->RiwayatHargaModel->where('id_tanah', $id_tanah)
                ->where('harga', 0.00)
                ->where('waktu', '0000-00-00')
                ->delete();

            return redirect()->to('/admin/dataPeta')->with('message', 'Harga tanah berhasil diperbarui.');
        } else {
            return redirect()->to('/admin/dataPeta')->with('message', 'Gagal memperbarui harga tanah.');
        }    
    }

    public function delete($id_tanah)
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        // mengecek apakah data tanah ada
        $tanah = $this->TanahModel->find($id_tanah);
        if (!$tanah) {
            return redirect()->to('/admin/dataPeta')->with('error', 'Data tanah tidak ditemukan.');
        }

        // Hapus riwayat harga terkait
        $this->RiwayatHargaModel->where('id_tanah', $id_tanah)->delete();

        // Hapus titik koordinat terkait
        $this->TitikKoordinatModel->where('id_tanah', $id_tanah)->delete();

        // Hapus data tanah
        $this->TanahModel->delete($id_tanah);

        return redirect()->to('/admin/dataPeta')->with('message', 'Data berhasil dihapus.');
    }

    public function search()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $model = new TanahModel();

        // Ambil seluruh data tanah untuk rekomendasi
        $allTanahData = $model->getAll();

        // Ambil parameter pencarian dari input GET
        $searchKeyword = $this->request->getGet('searchInput');

        // Filter data berdasarkan keyword pencarian
        $filters = [];
        if (!empty($searchKeyword)) {
            $filters = [
                'wilayah' => $searchKeyword,
                'kecamatan' => $searchKeyword,
                'kelurahan' => $searchKeyword
            ];
        }

        // Ambil data tanah berdasarkan filter
        $tanahData = $model->getAll($filters, false, true);

        // Hitung harga min dan max
        $hargaTerkiniArray = array_column($tanahData, 'harga_terkini');
        $hargaMin = !empty($hargaTerkiniArray) ? min($hargaTerkiniArray) : 0;
        $hargaMax = !empty($hargaTerkiniArray) ? max($hargaTerkiniArray) : 0;

        // Buat GeoJSON dari data tanah
        $geoJson = [
            "type" => "FeatureCollection",
            "features" => []
        ];

        $features = [];

        foreach ($tanahData as $tanah) {
            $coordinates = $this->TitikKoordinatModel->where('id_tanah', $tanah['id_tanah'])->findAll();
            
            if (!empty($coordinates)) {
                $polygonCoordinates = [];
                foreach ($coordinates as $coordinate) {
                    $polygonCoordinates[] = [(float)$coordinate['longitude'], (float)$coordinate['latitude']];
                }

                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [$polygonCoordinates],
                    ],
                    'properties' => [
                        'penggunaan' => $tanah['kawasan'],
                        'kecamatan' => $tanah['kecamatan'],
                        'wilayah' => $tanah['wilayah'],
                        'kelurahan' => $tanah['kelurahan'],
                        'waktu_terkini' => $tanah['waktu_terkini'],
                        'harga_terkini' => $tanah['harga_terkini']
                    ],
                ];

                $hargaList[] = $tanah['harga_terkini'];
            }
        }

        $geoJson['features'] = $features;

        // Ambil titik referensi (objek/data pembanding) dari kolom titik_latitude & titik_longitude di model Tanah
        $titikReferensi = [];
        foreach ($tanahData as $tanah) {
            if (!empty($tanah['titik_referensi']['titik_latitude']) && !empty($tanah['titik_referensi']['titik_longitude'])) {
                $titikReferensi[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            (float)$tanah['titik_referensi']['titik_longitude'], 
                            (float)$tanah['titik_referensi']['titik_latitude']
                        ]
                    ],
                    'properties' => [
                        'penggunaan' => $tanah['kawasan'],
                        'kecamatan' => $tanah['kecamatan'],
                        'wilayah' => $tanah['wilayah'],
                        'kelurahan' => $tanah['kelurahan'],
                        'waktu_terkini' => $tanah['waktu_terkini'],
                        'harga_terkini' => $tanah['harga_terkini'],
                        'indikasi' => $tanah['indikasi'], 
                        'luas_tanah' => $tanah['luas_tanah'],
                        'luas_bangunan' => $tanah['luas_bangunan'],
                        'nama_pemilik' => $tanah['nama_pemilik'],
                        'no_telp' => $tanah['no_telp'],
                        'keterangan' => 'Titik Referensi ' . $tanah['kelurahan'],
                    ]
                ];
            }
        }

        $currentRoute = current_url();
        $routeName = explode('/', $currentRoute);

        // Kirim data ke view
        return $this->render('admin/peta/search', [
            'routeName' => end($routeName),
            'hargaMin' => $hargaMin,
            'hargaMax' => $hargaMax,
            'geoJsonData' => json_encode($geoJson),
            'allTanahData' => $allTanahData, // Kirim seluruh data tanah untuk rekomendasi
            'searchKeyword' => $searchKeyword, // Kirim keyword pencarian ke view
            'rekomendasi' => json_encode($allTanahData, JSON_HEX_APOS | JSON_HEX_QUOT), // Gunakan seluruh data tanah untuk rekomendasi
            'titikReferensiData' => json_encode(['type' => 'FeatureCollection', 'features' => $titikReferensi])
        ]);
    }

    // fungsi export file
    public function exportToExcel()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        // mengambil filter parameter
        $startDate = $this->request->getGet('startDate');
        $endDate = $this->request->getGet('endDate');
        $format = $this->request->getGet('format');
        $exportAll = $this->request->getGet('exportAll') === 'true';

        // membuat excel baru
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator("GIS")
            ->setLastModifiedBy("GIS")
            ->setTitle("Data Peta " . ($startDate ? 'dari ' . $startDate : '') . ($endDate ? ' sampai ' . $endDate : ''))
            ->setSubject("Data Peta")
            ->setDescription("Data Peta Export")
            ->setKeywords("peta tanah export")
            ->setCategory("Export");

        // mengatur header
        $headers = [
            'No', 'Penggunaan Tanah', 'Indikasi', 'Titik Latitude', 'Titik Longitude', 
            'Desa/Kelurahan', 'Kecamatan', 'Kabupaten/Kota', 'Tgl/Th Baru', 
            'Harga Terkini', 'Luas Tanah', 'Luas Bangunan', 'Nama Pemilik/Agen', 
            'No Telepon'
        ];
        
        if ($exportAll) {
            $headers = array_merge($headers, ['Riwayat Harga']);
        }
        
        $sheet->fromArray([$headers], NULL, 'A1');

        // mengambil filter data
        $builder = $this->TanahModel->builder();
        
        if (!empty($startDate) && !$exportAll) {
            $builder->where('waktu_terkini >=', $startDate);
        }
        
        if (!empty($endDate) && !$exportAll) {
            $builder->where('waktu_terkini <=', $endDate);
        }

        $tanahData = $builder->get()->getResultArray();

        // menambahkan data ke dalam excel
        $row = 2;
        foreach ($tanahData as $index => $tanah) {
            $data = [
                $index + 1,
                $tanah['kawasan'] ?? '',
                $tanah['indikasi'] ?? '',
                $tanah['titik_latitude'] ?? 0.00000,
                $tanah['titik_longitude'] ?? 0.00000,
                $tanah['kelurahan'] ?? '',
                $tanah['kecamatan'] ?? '',
                $tanah['wilayah'] ?? '',
                $tanah['waktu_terkini'] ?? '',
                $tanah['harga_terkini'] ?? 0,
                $tanah['luas_tanah'] ?? 0,
                $tanah['luas_bangunan'] ?? 0,
                $tanah['nama_pemilik'] ?? '',
                $tanah['no_telp'] ?? ''
            ];

            if ($exportAll) {
                $riwayatHarga = $this->RiwayatHargaModel->where('id_tanah', $tanah['id_tanah'])
                    ->where('waktu !=', '0000-00-00')
                    ->where('harga !=', 0.00)
                    ->orderBy('waktu', 'DESC')
                    ->findAll();
                
                $riwayatText = [];
                foreach ($riwayatHarga as $riwayat) {
                    $riwayatText[] = date('d/m/Y', strtotime($riwayat['waktu'])) . ': ' . 
                                    number_format($riwayat['harga'], 2, ',', '.');
                }
                
                $data[] = implode("\n", $riwayatText);
            }

            $sheet->fromArray([$data], NULL, 'A' . $row);
            $row++;
        }

        // Auto size columns
        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Set wrap text untuk kolom riwayat
        if ($exportAll) {
            $sheet->getStyle('O2:O' . $sheet->getHighestRow())
                ->getAlignment()->setWrapText(true);
        }

        // Set header style
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['outline' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($headerStyle);

        // mengatur orientasi kertas ke landscape
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        
        // Set fit to page width
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        // Export to multiple formats
        $filename = 'data_peta_' . ($exportAll ? 'all' : ($startDate ? date('Ymd', strtotime($startDate)) : 'all'));
        $filename .= ($exportAll ? '' : ($endDate ? '_to_' . date('Ymd', strtotime($endDate)) : ''));
        $filename .= '_' . date('His');
        
        if ($format === 'pdf') {
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');
            
            // Additional PDF configuration for landscape
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
            $writer->save('php://output');
        } else {
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            $writer->save('php://output');
        }
        exit();
    }
        
    public function importFromExcel()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $id_user = session()->get('id_user');
        
        // Validasi file upload
        $validation = \Config\Services::validation();
        $validation->setRules([
            'file_excel' => [
                'label' => 'File Excel',
                'rules' => 'uploaded[file_excel]|ext_in[file_excel,xlsx,xls]|max_size[file_excel,3072]',
                'errors' => [
                    'uploaded' => 'Harus memilih file excel',
                    'ext_in' => 'File harus berformat xlsx atau xls',
                    'max_size' => 'Ukuran file maksimal 3MB'
                ]
            ]
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $file = $this->request->getFile('file_excel');
        
        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            
            // Lewati header
            array_shift($rows);
            
            $db = \Config\Database::connect();
            $db->transStart();
            
            foreach ($rows as $row) {
                // Skip jika baris kosong atau data penting tidak ada
                if (empty(array_filter($row, function($value) { 
                    return $value !== null && $value !== ''; 
                }))) {
                    continue;
                }

                // Skip jika data penting kosong (misal: kelurahan, kecamatan, atau koordinat)
                if (empty($row[1]) || empty($row[5]) || empty($row[6]) || empty($row[3]) || empty($row[4])) {
                    continue;
                }

                // Data utama tanah
                $tanahData = [
                    'id_user' => $id_user,
                    'kawasan' => $row[1] ?? '', // Kolom B (Penggunaan Tanah)
                    'indikasi' => $row[2] ?? '', // Kolom C (Indikasi)
                    'kelurahan' => $row[5] ?? '', // Kolom F (Desa/Kelurahan)
                    'kecamatan' => $row[6] ?? '', // Kolom G (Kecamatan)
                    'wilayah' => $row[7] ?? '', // Kolom H (Kabupaten/Kota)
                    'waktu_terkini' => $row[8] ?? date('Y-m-d'), // Kolom I (Tgl/Th Baru)
                    'harga_terkini' => $row[9] ?? 0, // Kolom J (Harga Terkini)
                    'luas_tanah' => $row[10] ?? 0, // Kolom K (Luas Tanah)
                    'luas_bangunan' => $row[11] ?? 0, // Kolom L (Luas Bangunan)
                    'nama_pemilik' => $row[12] ?? '', // Kolom M (Nama Pemilik/Agen)
                    'no_telp' => $row[13] ?? '', // Kolom N (No Telepon)
                    'titik_latitude' => $row[3] ?? 0.00000, // Kolom D (Titik Latitude referensi)
                    'titik_longitude' => $row[4] ?? 0.00000, // Kolom E (Titik Longitude referensi)
                ];
                
                // memasukkan data tanah
                $id_tanah = $this->TanahModel->insert($tanahData);
            
                // Jika koordinat referensi valid, buat polygon otomatis
                if ($tanahData['titik_latitude'] != 0.00000 && $tanahData['titik_longitude'] != 0.00000) {
                    // Hapus titik koordinat default jika ada
                    $this->TitikKoordinatModel->where('id_tanah', $id_tanah)->delete();
                    
                    // Generate polygon otomatis
                    $this->generatePolygon($id_tanah);
                }
                
                // Proses riwayat harga tambahan jika ada (kolom O dan seterusnya)
                if (isset($row[14])) {
                    $riwayatHarga = is_array($row[14]) ? $row[14] : explode("\n", (string)$row[14]);
                    
                    foreach ($riwayatHarga as $riwayat) {
                        if (is_string($riwayat) && strpos($riwayat, ':') !== false) {
                            list($tanggal, $harga) = explode(':', $riwayat, 2);
                            
                            $tanggal = trim($tanggal);
                            $harga = trim(str_replace(['Rp', '.', ','], '', $harga));
                            
                            // Validasi tanggal dan harga
                            $dateObj = \DateTime::createFromFormat('d/m/Y', $tanggal);
                            if ($dateObj && is_numeric($harga)) {
                                $tanggalFormatted = $dateObj->format('Y-m-d');
                                $harga = (float)$harga;
                                
                                if ($harga > 0) {
                                    $this->RiwayatHargaModel->insert([
                                        'id_tanah' => $id_tanah,
                                        'waktu' => $tanggalFormatted,
                                        'harga' => $harga
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data');
            }
            
            return redirect()->to('/admin/dataPeta')->with('message', 'Data berhasil diimpor!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}