<?php

namespace App\Models;

use CodeIgniter\Model;

class TanahModel extends Model
{
    protected $table = 'tbl_Tanah';
    protected $primaryKey = 'id_tanah';
    protected $allowedFields = ['id_user', 'kawasan', 'kecamatan', 'wilayah', 'kelurahan', 'harga_terkini', 'waktu_terkini', 'indikasi', 'titik_latitude', 'titik_longitude', 'luas_tanah', 'luas_bangunan', 'no_telp', 'nama_pemilik'];

    //fungsi digunakan untuk memanggil id_user dari tabel tbl_users
    public function getUser($id_user)
    {
        return $this->db->table('users')->where('id_user', $id_user)->get()->getRowArray();
    }
    
    public function getAll($filters = [], $strictKelurahan = false, $excludeDefaultCoordinates = false)
    {
        //memanggil tabel riwayat harga dan titik koordinat
        $builder = $this->db->table($this->table);
        $builder->select('tbl_Tanah.*, tbl_Riwayat_harga.waktu, tbl_Riwayat_harga.harga, tbl_Titik_koordinat.longitude, tbl_Titik_koordinat.latitude');
        $builder->join('tbl_Riwayat_harga', 'tbl_Riwayat_harga.id_tanah = tbl_Tanah.id_tanah', 'left');
        $builder->join('tbl_Titik_koordinat', 'tbl_Titik_koordinat.id_tanah = tbl_Tanah.id_tanah', 'left');

        if (!empty($filters)) {
            $builder->groupStart();
            foreach ($filters as $key => $value) {
                if (!empty($value)) {
                    if ($strictKelurahan && $key !== 'kelurahan') {
                        continue; // Lewati filter selain kelurahan jika strictKelurahan aktif
                    }
                    $builder->orLike($key, $value);
                }
            }
            $builder->groupEnd();
        }

        // Filter koordinat hanya berlaku untuk data koordinat yang bernilai default
        if ($excludeDefaultCoordinates) {
            $builder->groupStart()
                ->where('tbl_Titik_koordinat.latitude IS NULL')
                ->orWhere('tbl_Titik_koordinat.latitude !=', '0.0000')
                ->orWhere('tbl_Titik_koordinat.longitude !=', '0.0000')
                ->groupEnd();
        }

        $builder->orderBy('tbl_Tanah.id_tanah', 'DESC');

        $results = $builder->get()->getResultArray();

        // pengelompokkan data berdasarkan id_tanah
        $groupedData = [];
        foreach ($results as $row) {
            $id_tanah = $row['id_tanah'];
            if (!isset($groupedData[$id_tanah])) {
                $groupedData[$id_tanah] = [
                    'id_tanah' => $row['id_tanah'],
                    'kawasan' => $row['kawasan'],
                    'kecamatan' => $row['kecamatan'],
                    'wilayah' => $row['wilayah'],
                    'kelurahan' => $row['kelurahan'],
                    'waktu_terkini' => $row['waktu_terkini'],
                    'harga_terkini' => $row['harga_terkini'],
                    'indikasi' => $row['indikasi'],
                    'harga' => $row['harga'],
                    'waktu' => $row['waktu'],
                    //mengelompok titik koordinat dalam satu variabel yaitu titik referensi (objek/data pembanding)
                    'titik_referensi' => [
                        'titik_latitude' => $row['titik_latitude'],
                        'titik_longitude' => $row['titik_longitude']
                    ],
                    'luas_tanah' => $row['luas_tanah'],
                    'luas_bangunan' => $row['luas_bangunan'],
                    'no_telp' => $row['no_telp'],
                    'nama_pemilik' => $row['nama_pemilik'],
                    'coordinates' => [] // buat longitude dan latitude dalam array coordinates
                ];
            }
            // Tambahkan koordinat ke dalam array jika ada (tbl_koordinat)
            if (!is_null($row['longitude']) && !is_null($row['latitude'])) {
                $groupedData[$id_tanah]['coordinates'][] = [
                    'longitude' => $row['longitude'],
                    'latitude' => $row['latitude']
                ];
            }
        }

        return array_values($groupedData);
    }

    public function getRiwayatHarga($filters = [])
    {
        $builder = $this->db->table('tbl_Riwayat_harga');
        $builder->select('tbl_Riwayat_harga.*, tbl_Tanah.kecamatan, tbl_Tanah.wilayah, tbl_Tanah.kelurahan');
        $builder->join('tbl_Tanah', 'tbl_Tanah.id_tanah = tbl_Riwayat_harga.id_tanah', 'left');

        // Filter berdasarkan wilayah, kecamatan, atau kelurahan
        if (!empty($filters) && is_array($filters)) { // Pastikan filters adalah array
            if (isset($filters['wilayah'])) { // Pastikan 'wilayah' ada dalam array
                $filterValue = $filters['wilayah']; // Nilai filter sama untuk semua kolom
                $builder->groupStart()
                    ->like('tbl_Tanah.wilayah', $filterValue)
                    ->orLike('tbl_Tanah.kecamatan', $filterValue)
                    ->orLike('tbl_Tanah.kelurahan', $filterValue)
                    ->groupEnd();
            }
        }

        // Hanya ambil data dengan waktu yang valid (bukan '0000-00-00')
        $builder->where("tbl_Riwayat_harga.waktu != '0000-00-00'");
        $builder->where("tbl_Riwayat_harga.waktu IS NOT NULL");

        // Urutkan berdasarkan waktu
        $builder->orderBy('tbl_Riwayat_harga.waktu', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function countTanah()
    {
        return $this->db->table('tbl_Tanah')->countAllResults();
    }

    public function getAverageHargaByWilayah()
    {
        $builder = $this->db->table($this->table);

        // Menghitung rata-rata harga dan mengelompokkan berdasarkan wilayah
        $builder->select('wilayah, AVG(harga_terkini) AS rata_rata_harga');
        $builder->groupBy('wilayah'); // Mengelompokkan berdasarkan kolom wilayah
        $result = $builder->get()->getResultArray();

        // Mengembalikan hasil dalam bentuk array asosiatif
        $rataRataHargaByWilayah = [];
        foreach ($result as $row) {
            $rataRataHargaByWilayah[$row['wilayah']] = $row['rata_rata_harga'];
        }

        return $rataRataHargaByWilayah;
    }

    public function getHargaTertinggiByKelurahan()
    {
        $builder = $this->db->table($this->table);
        $builder->select('kelurahan, MAX(harga_terkini) AS harga_tertinggi');
        $builder->groupBy('kelurahan');
        $builder->orderBy('harga_tertinggi', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getHargaTertinggiByKecamatan()
    {
        $builder = $this->db->table($this->table);
        $builder->select('kecamatan, MAX(harga_terkini) AS harga_tertinggi');
        $builder->groupBy('kecamatan');
        $builder->orderBy('harga_tertinggi', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getTop5HargaTertinggi()
    {
        return $this->db->table($this->table)
            ->select('kecamatan, kelurahan, harga_terkini')
            ->where('harga_terkini !=', 0)
            ->orderBy('harga_terkini', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
    }

    public function getTop5HargaTerendah()
    {
        return $this->db->table($this->table)
            ->select('kecamatan, kelurahan, harga_terkini')
            ->where('harga_terkini !=', 0)
            ->orderBy('harga_terkini', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();
    }
}