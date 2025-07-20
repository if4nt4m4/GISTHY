<?php

namespace App\Controllers\admin;

use App\Controllers\admin\BaseController;
use App\Models\TanahModel;
use App\Models\RiwayatHargaModel;

class RiwayatHargactrl extends BaseController
{
    public function index() 
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $model = new TanahModel();

        // Ambil parameter filter dari input GET
        $filter = $this->request->getGet('filter');

        // Siapkan filter
        $filters = [];
        if (!empty($filter)) {
            $filters['wilayah'] = $filter;
            $filters['kecamatan'] = $filter;
            $filters['kelurahan'] = $filter;
        }

        // Ambil data riwayat harga
        $riwayatHarga = $model->getRiwayatHarga($filters);

        //rekomendasi list input search
        $wilayahList = $model->select('wilayah')->distinct()->findAll();
        $kecamatanList = $model->select('kecamatan')->distinct()->findAll();
        $kelurahanList = $model->select('kelurahan')->distinct()->findAll();

        // Format data untuk grafik
        $labels = [];
        $dataHarga = [];
        foreach ($riwayatHarga as $riwayat) {
            // Pastikan tanggal valid sebelum memformat
            if ($riwayat['waktu'] && $riwayat['waktu'] != '0000-00-00') {
                $labels[] = date('d M Y', strtotime($riwayat['waktu'])); // Format tanggal
                $dataHarga[] = $riwayat['harga'];
            }
        }

        $currentRoute = current_url(); // Mendapatkan URL lengkap
        $routeName = explode('/', $currentRoute); // Memecah URL untuk mendapatkan bagian rutenya

        // Mengoper data ke view
        return $this->render('admin/riwayat/index', [
            'routeName' => end($routeName),
            'labels' => json_encode($labels), // Data untuk sumbu X (tanggal)
            'dataHarga' => json_encode($dataHarga), // Data untuk sumbu Y (harga)
            'filter' => $filter, // Kirim nilai filter ke view
            'wilayahList' => $wilayahList,
            'kecamatanList' => $kecamatanList,
            'kelurahanList' => $kelurahanList
        ]);
    }

    public function show()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('/gate/login'));
        }

        $currentRoute = current_url();
        $routeName = explode('/', $currentRoute);

        $tanahModel = new TanahModel();
        $riwayatHarga = new RiwayatHargaModel();

        $searchInput = $this->request->getGet('searchInput');

        $filters = [];
        if (!empty($searchInput)) {
            $filters['kelurahan'] = $searchInput;
        }

        // Ambil data tanah
        $tanah = $tanahModel->getAll($filters, false, true);
        
        foreach ($tanah as &$tanahItem) {
            if (isset($tanahItem['id_tanah']) && is_numeric($tanahItem['id_tanah'])) {
                $riwayat = $riwayatHarga->where('id_tanah', $tanahItem['id_tanah'])->findAll();
                $tanahItem['riwayat_harga'] = $riwayat;
            } else {
                $tanahItem['riwayat_harga'] = [];
            }
        }

        $allTanahData = $tanahModel->getAll();

        // Ambil harga minimum dan maksimum
        $hargaMin = $tanahModel->selectMin('harga_terkini')->first()['harga_terkini'] ?? 0;
        $hargaMax = $tanahModel->selectMax('harga_terkini')->first()['harga_terkini'] ?? 0;

        // Konversi data tanah ke format GeoJSON
        $geoJsonData = $this->convertToGeoJson($tanah);

        // Ambil dan konversi titik referensi
        $titikReferensi = $this->getTitikReferensi($tanah);
        $titikReferensiData = $this->convertTitikReferensiToGeoJson($titikReferensi);

        return $this->render('admin/riwayat/show', [
            'routeName' => end($routeName),
            'tanah' => $tanah,
            'allTanahData' => $allTanahData,
            'hargaMin' => $hargaMin,
            'hargaMax' => $hargaMax,
            'geoJsonData' => $geoJsonData,
            'titikReferensiData' => $titikReferensiData,
            'searchKeyword' => $searchInput,
        ]);
    }

    private function getTitikReferensi($tanah)
    {
        $titikReferensi = [];
        
        foreach ($tanah as $data) {
            if (!empty($data['titik_referensi']['titik_latitude']) && !empty($data['titik_referensi']['titik_longitude'])) {
                $titikReferensi[] = [
                    'latitude' => $data['titik_referensi']['titik_latitude'],
                    'longitude' => $data['titik_referensi']['titik_longitude'],
                    'kelurahan' => $data['kelurahan'],
                    'kecamatan' => $data['kecamatan'],
                    'nama_pemilik' => $data['nama_pemilik']
                ];
            }
        }
        
        return $titikReferensi;
    }

    private function convertTitikReferensiToGeoJson($titikReferensi)
    {
        $features = [];

        foreach ($titikReferensi as $data) {
            if (!empty($data['latitude']) && !empty($data['longitude'])) {
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            (float)$data['longitude'],
                            (float)$data['latitude']
                        ]
                    ],
                    'properties' => [
                        'kelurahan' => $data['kelurahan'] ?? '',
                        'kecamatan' => $data['kecamatan'] ?? '',
                        'nama_pemilik' => $data['nama_pemilik'] ?? ''
                    ]
                ];
            }
        }

        return json_encode(['type' => 'FeatureCollection', 'features' => $features]);
    }

    private function convertToGeoJson($tanah)
    {
        $features = [];

        foreach ($tanah as $data) {
            if (!empty($data['coordinates'])) {
                // Ambil koordinat mentah
                $coords = array_map(fn($coord) => [
                    'lon' => (float)$coord['longitude'],
                    'lat' => (float)$coord['latitude']
                ], $data['coordinates']);

                // Hitung centroid
                $centroid = ['lon' => 0, 'lat' => 0];
                foreach ($coords as $pt) {
                    $centroid['lon'] += $pt['lon'];
                    $centroid['lat'] += $pt['lat'];
                }
                $centroid['lon'] /= count($coords);
                $centroid['lat'] /= count($coords);

                // Urutkan berdasarkan sudut terhadap centroid (polar angle sorting)
                usort($coords, function ($a, $b) use ($centroid) {
                    $angleA = atan2($a['lat'] - $centroid['lat'], $a['lon'] - $centroid['lon']);
                    $angleB = atan2($b['lat'] - $centroid['lat'], $b['lon'] - $centroid['lon']);
                    return $angleA <=> $angleB;
                });

                // Ubah ke format GeoJSON [lon, lat]
                $polygonCoordinates = array_map(fn($pt) => [$pt['lon'], $pt['lat']], $coords);

                // Tutup polygon (titik awal = titik akhir)
                if ($polygonCoordinates[0] !== end($polygonCoordinates)) {
                    $polygonCoordinates[] = $polygonCoordinates[0];
                }

                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [$polygonCoordinates],
                    ],
                    'properties' => [
                        'kawasan' => $data['kawasan'],
                        'wilayah' => $data['wilayah'],
                        'kecamatan' => $data['kecamatan'],
                        'kelurahan' => $data['kelurahan'],
                        'waktu_terkini' => $data['waktu_terkini'],
                        'harga_terkini' => $data['harga_terkini'],
                    ],
                ];
            }
        }

        return json_encode(['type' => 'FeatureCollection', 'features' => $features]);
    }
}