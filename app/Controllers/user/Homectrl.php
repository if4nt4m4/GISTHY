<?php

namespace App\Controllers\user;

use App\Controllers\user\BaseController;
use App\Models\ProfilPerusahaanModel;
use App\Models\SliderModel;
use App\Models\TanahModel;
use App\Models\TitikKoordinatModel;

class Homectrl extends BaseController
{
    protected $ProfilModel;
    protected $TanahModel;
    protected $SliderModel;
    protected $TitikKoordinatModel;

    public function __construct() {
        $this->ProfilModel = new ProfilPerusahaanModel();
        $this->TanahModel = new TanahModel();
        $this->SliderModel = new SliderModel();
        $this->TitikKoordinatModel = new TitikKoordinatModel();
    }

    public function index()
    {
        $profil = $this->ProfilModel->findAll();
        $tanahList = $this->TanahModel->getAll(null, false, true);
        $slider = $this->SliderModel->findAll();

        $features = [];
        $markers = [];

        foreach ($tanahList as $tanah) {
            // Data untuk polygon
            if (!empty($tanah['coordinates'])) {
                $polygonCoordinates = [];
                foreach ($tanah['coordinates'] as $coordinate) {
                    $polygonCoordinates[] = [(float)$coordinate['longitude'], (float)$coordinate['latitude']];
                }

                if (count($polygonCoordinates) > 0) {
                    // Tutup polygon jika belum tertutup
                    if ($polygonCoordinates[0][0] !== $polygonCoordinates[count($polygonCoordinates)-1][0] || 
                        $polygonCoordinates[0][1] !== $polygonCoordinates[count($polygonCoordinates)-1][1]) {
                        $polygonCoordinates[] = $polygonCoordinates[0];
                    }

                    // Hitung titik tengah polygon
                    $center = $this->calculatePolygonCenter($polygonCoordinates);

                    $features[] = [
                        'type' => 'Feature',
                        'geometry' => [
                            'type' => 'Polygon',
                            'coordinates' => [$polygonCoordinates],
                        ],
                        'properties' => [
                            'kawasan' => $tanah['kawasan'],
                            'kecamatan' => $tanah['kecamatan'],
                            'wilayah' => $tanah['wilayah'],
                            'kelurahan' => $tanah['kelurahan'],
                            'center' => $center // Simpan titik tengah di properti
                        ],
                    ];

                    // Buat marker dari titik tengah polygon
                    $markers[] = [
                        'type' => 'Feature',
                        'geometry' => [
                            'type' => 'Point',
                            'coordinates' => [
                                $center['lng'],
                                $center['lat']
                            ]
                        ],
                        'properties' => [
                            'kawasan' => $tanah['kawasan'],
                            'kecamatan' => $tanah['kecamatan'],
                            'wilayah' => $tanah['wilayah'],
                            'kelurahan' => $tanah['kelurahan'],
                            'popupContent' => "Kawasan: {$tanah['kawasan']}<br>Kelurahan: {$tanah['kelurahan']}<br>Kecamatan: {$tanah['kecamatan']}"
                        ]
                    ];
                }
            }
        }

        return $this->render('user/home/index', [
            'profil' => $profil,
            'slider' => $slider,
            'geoJsonData' => json_encode([
                'type' => 'FeatureCollection', 
                'features' => $features
            ]),
            'markerData' => json_encode([
                'type' => 'FeatureCollection',
                'features' => $markers
            ])
        ]);
    }

    /**
     * Menghitung titik tengah (centroid) dari polygon
     */
    private function calculatePolygonCenter($coordinates) {
        $latSum = 0;
        $lngSum = 0;
        $count = count($coordinates);
        
        foreach ($coordinates as $point) {
            $lngSum += $point[0];
            $latSum += $point[1];
        }
        
        return [
            'lat' => $latSum / $count,
            'lng' => $lngSum / $count
        ];
    }
}